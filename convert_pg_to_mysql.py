
import sys
import re

def convert_pg_to_mysql(input_file, output_file):
    backtick = chr(96)
    with open(input_file, 'r', encoding='utf-8') as f_in, open(output_file, 'w', encoding='utf-8') as f_out:
        
        in_copy_mode = False
        
        f_out.write("-- Converted from PostgreSQL to MySQL\n")
        f_out.write("SET FOREIGN_KEY_CHECKS=0;\n")
        f_out.write("SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n")
        f_out.write("START TRANSACTION;\n")
        f_out.write("SET time_zone = \"+00:00\";\n\n")

        lines = f_in.readlines()
        i = 0
        while i < len(lines):
            line = lines[i]
            stripped = line.strip()
            
            # Skip PG specific SET/SELECT
            if stripped.startswith("SET ") or stripped.startswith("SELECT pg_catalog"):
                i += 1
                continue
            if stripped.startswith("\\connect") or stripped.startswith("\\restrict"):
                i += 1
                continue
                
            # Handle COPY
            if stripped.startswith("COPY "):
                # Format: COPY public.tablename (col1, col2) FROM stdin;
                match = re.search(r"COPY (?:public\.)?(\w+) \((.*?)\) FROM stdin;", stripped)
                if match:
                    table_name = match.group(1)
                    columns = match.group(2).replace('"', backtick)
                    in_copy_mode = True
                    i += 1
                    f_out.write(f"-- Data for table {table_name}\n")
                    f_out.write(f"LOCK TABLES {backtick}{table_name}{backtick} WRITE;\n")
                    f_out.write(f"/*!40000 ALTER TABLE {backtick}{table_name}{backtick} DISABLE KEYS */;\n")
                    
                    # Accumulate inserts
                    insert_prefix = f"INSERT INTO {backtick}{table_name}{backtick} ({columns}) VALUES "
                    
                    batch_values = []
                    
                    while i < len(lines):
                        dline = lines[i].strip()
                        if dline == "\\.":
                            break
                        
                        parts = lines[i].rstrip('\n').split('\t')
                        converted_parts = []
                        for part in parts:
                            if part == "\\N":
                                converted_parts.append("NULL")
                            else:
                                # Escape ' to '' for SQL
                                val = part.replace("'", "''")
                                val = val.replace("\\n", "\n").replace("\\r", "\r").replace("\\t", "\t").replace("\\\\", "\\")
                                converted_parts.append(f"'{val}'")
                        
                        batch_values.append(f"({', '.join(converted_parts)})")
                        
                        if len(batch_values) >= 100:
                            f_out.write(insert_prefix + ",\n".join(batch_values) + ";\n")
                            batch_values = []
                            
                        i += 1
                    
                    if batch_values:
                        f_out.write(insert_prefix + ",\n".join(batch_values) + ";\n")
                    
                    f_out.write(f"/*!40000 ALTER TABLE {backtick}{table_name}{backtick} ENABLE KEYS */;\n")
                    f_out.write(f"UNLOCK TABLES;\n")
                
                i += 1
                continue

            # Handle CREATE TABLE
            if stripped.startswith("CREATE TABLE "):
                # Remove public.
                line = line.replace("public.", "")
                
                # Check for table name and quote if needed?
                # CREATE TABLE assignments (
                # We can just leave it as is if it doesn't have quotes.
                
                j = i
                table_def = []
                while j < len(lines):
                    l = lines[j]
                    
                    # public. -> empty
                    l = l.replace("public.", "")
                    
                    # Type conversions
                    l = re.sub(r'character varying\(\d+\)', lambda m: m.group(0).replace('character varying', 'varchar'), l)
                    l = l.replace("character varying", "varchar(255)") 
                    l = l.replace("timestamp(0) without time zone", "timestamp")
                    l = l.replace("timestamp without time zone", "timestamp")
                    l = l.replace("smallint", "smallint") # same
                    
                    # Default values cleanup: regex to remove casting ::type
                    l = re.sub(r"::[\w\s]+(?:\(.*\))?", "", l)
                    
                    # Sequences handled via AUTO_INCREMENT
                    # Strict check: column named 'id', type bigint/int/integer, at start of line
                    # Example line: "    id bigint NOT NULL,"
                    if re.search(r'^\s*id\s+(?:bigint|integer|int)\s+NOT NULL', l):
                         l = re.sub(r'(id\s+(?:bigint|integer|int)\s+NOT NULL)', r'\1 AUTO_INCREMENT PRIMARY KEY', l)
                    
                    # Remove constraints that are PG specific inline checks
                    if "CONSTRAINT" in l and "CHECK" in l:
                        l = "-- " + l
                    
                    # Fix quoted identifiers "column" -> `column`
                    l = re.sub(r'"(\w+)"', f'{backtick}\\1{backtick}', l)

                    table_def.append(l)
                    
                    if l.strip().endswith(");"):
                        break
                    j += 1
                
                # Clean up comments
                clean_def = [x for x in table_def] # copy
                
                f_out.write("".join(clean_def) + "\n")
                i = j + 1
                continue

            # Handle Constraints / Alter Table
            if stripped.startswith("ALTER TABLE "):
                line = line.replace("public.", "")
                line = line.replace("ONLY ", "")
                
                if "OWNER TO" in line:
                    i += 1
                    continue
                
                if "ADD CONSTRAINT" in line and "PRIMARY KEY" in line:
                    # Comment out if for 'id' as we did it inline
                    if "(id)" in line:
                        f_out.write("-- " + line)
                    else:
                         f_out.write(line)
                    i += 1
                    continue
                
                if "ADD CONSTRAINT" in line and "FOREIGN KEY" in line:
                    f_out.write(line)
                    i += 1
                    continue

                if "ALTER COLUMN" in line and "SET DEFAULT nextval" in line:
                    f_out.write("-- " + line)
                    i += 1
                    continue

                f_out.write(line)
                i += 1
                continue
            
            if stripped.startswith("CREATE SEQUENCE") or stripped.startswith("ALTER SEQUENCE"):
                f_out.write("-- " + line)
                i += 1
                continue
                
            if stripped.startswith("COMMENT ON"):
                f_out.write("-- " + line)
                i += 1
                continue

            # Default write
            line = line.replace("public.", "")
            f_out.write(line)
            i += 1

        f_out.write("SET FOREIGN_KEY_CHECKS=1;\n")
        f_out.write("COMMIT;\n")

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python convert.py input.sql output.sql")
    else:
        convert_pg_to_mysql(sys.argv[1], sys.argv[2])
