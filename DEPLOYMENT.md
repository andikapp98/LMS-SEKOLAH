# 🚀 Deployment Guide - LMS SMKS Yasmu Gresik

## 📋 Pre-Deployment Checklist

### Development Completion
- [ ] All features tested locally
- [ ] No critical bugs
- [ ] Mobile responsive verified
- [ ] File upload/download working
- [ ] Database migrations complete
- [ ] Seeders tested

### Code Preparation
- [ ] Remove debug code
- [ ] Remove console.log statements
- [ ] Optimize images
- [ ] Clean unused dependencies
- [ ] Update version number

---

## 🔒 Security Configuration

### 1. Environment Variables (.env)

Create production `.env.production`:

```env
# Application
APP_NAME="LMS SMKS Yasmu Gresik"
APP_ENV=production
APP_KEY=base64:GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://lms.smksyasmu.sch.id

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=lms_production
DB_USERNAME=lms_user
DB_PASSWORD=STRONG_PASSWORD_HERE

# Session & Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true

CACHE_DRIVER=file
QUEUE_CONNECTION=database

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=noreply@smksyasmu.sch.id
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@smksyasmu.sch.id
MAIL_FROM_NAME="${APP_NAME}"

# Filesystem
FILESYSTEM_DISK=public

# Logging
LOG_CHANNEL=daily
LOG_LEVEL=error
```

### 2. Generate Application Key

```bash
php artisan key:generate --show

# Copy output ke APP_KEY di .env
```

### 3. Security Headers

Create `.htaccess` di public folder (Apache):

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    # Hide server info
    Header unset X-Powered-By
    Header set X-Powered-By "SMKS Yasmu"
    
    # Security headers
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
    Header set Permissions-Policy "geolocation=(), microphone=(), camera=()"
    
    # Force HTTPS (uncomment when SSL ready)
    # Header set Strict-Transport-Security "max-age=31536000; includeSubDomains"
</IfModule>

# Protect sensitive files
<FilesMatch "(^#.*#|\.(bak|config|dist|fla|inc|ini|log|psd|sh|sql|sw[op])|~)$">
    Require all denied
</FilesMatch>

# Prevent access to .env
<Files .env>
    Require all denied
</Files>
```

---

## 📦 Build Process

### 1. Install Production Dependencies

```bash
# PHP dependencies (no dev packages)
composer install --no-dev --optimize-autoloader --no-interaction

# JavaScript dependencies
npm ci --production=false
```

### 2. Build Frontend Assets

```bash
# Build untuk production
npm run build

# Verify build
ls -lh public/build/
```

### 3. Optimize Application

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views (optional, auto-cached in production)
php artisan view:cache

# Optimize composer autoloader
composer dump-autoload --optimize
```

### 4. Run Tests (Optional)

```bash
# Run tests before deployment
php artisan test

# Or PHPUnit
./vendor/bin/phpunit
```

---

## 🌐 Server Setup

### Option 1: Shared Hosting (cPanel)

#### Step 1: Upload Files

1. **Compress project:**
   ```bash
   # Exclude unnecessary files
   tar --exclude='node_modules' \
       --exclude='vendor' \
       --exclude='.git' \
       --exclude='storage/logs/*' \
       --exclude='storage/framework/cache/*' \
       --exclude='storage/framework/sessions/*' \
       --exclude='storage/framework/views/*' \
       -czf lms-deploy.tar.gz .
   ```

2. **Upload via FTP/cPanel File Manager**
   - Upload `lms-deploy.tar.gz` ke home directory
   - Extract: `tar -xzf lms-deploy.tar.gz`

#### Step 2: Move to Public HTML

```bash
# Move Laravel to parent directory
mv lms-sekolah/* ~/
mv lms-sekolah/.* ~/

# Point public_html to Laravel public folder
rm -rf ~/public_html
ln -s ~/public ~/public_html
```

#### Step 3: Set Permissions

```bash
chmod -R 755 ~/storage
chmod -R 755 ~/bootstrap/cache
```

#### Step 4: Install Composer Dependencies

```bash
cd ~/
composer install --no-dev --optimize-autoloader
```

#### Step 5: Setup Database

1. Create database via cPanel MySQL
2. Create user and grant privileges
3. Update `.env` with credentials

#### Step 6: Run Migrations

```bash
php artisan migrate --force
php artisan db:seed --class=CourseSeeder
php artisan storage:link
```

#### Step 7: Setup Cron Jobs (cPanel)

Add in cPanel Cron Jobs:
```
* * * * * cd /home/username/path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

### Option 2: VPS (Ubuntu 22.04)

#### Step 1: Server Preparation

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx mysql-server php8.2-fpm php8.2-mysql \
php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-curl \
php8.2-gd php8.2-zip php8.2-intl unzip git curl
```

#### Step 2: Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

#### Step 3: Install Node.js

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
node --version
npm --version
```

#### Step 4: Setup MySQL

```bash
# Secure MySQL
sudo mysql_secure_installation

# Create database
sudo mysql -u root -p

# In MySQL:
CREATE DATABASE lms_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'lms_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD';
GRANT ALL PRIVILEGES ON lms_production.* TO 'lms_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Step 5: Deploy Application

```bash
# Clone or upload to server
cd /var/www/
sudo git clone https://github.com/yourusername/lms-sekolah.git
cd lms-sekolah

# Or upload via SCP
scp -r /path/to/local/lms-sekolah user@server:/var/www/

# Set ownership
sudo chown -R www-data:www-data /var/www/lms-sekolah
sudo chmod -R 755 /var/www/lms-sekolah/storage
sudo chmod -R 755 /var/www/lms-sekolah/bootstrap/cache
```

#### Step 6: Install Dependencies

```bash
cd /var/www/lms-sekolah

# PHP dependencies
composer install --no-dev --optimize-autoloader

# Node dependencies and build
npm ci
npm run build
```

#### Step 7: Configure Environment

```bash
# Copy and edit .env
cp .env.example .env
nano .env  # Edit dengan production settings

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate --force
php artisan db:seed --class=CourseSeeder
php artisan storage:link

# Cache
php artisan config:cache
php artisan route:cache
```

#### Step 8: Configure Nginx

Create site config:
```bash
sudo nano /etc/nginx/sites-available/lms
```

Add configuration:
```nginx
server {
    listen 80;
    server_name lms.smksyasmu.sch.id;
    root /var/www/lms-sekolah/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Increase upload size
    client_max_body_size 10M;
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/lms /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

#### Step 9: SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Get certificate
sudo certbot --nginx -d lms.smksyasmu.sch.id

# Auto-renewal test
sudo certbot renew --dry-run
```

#### Step 10: Setup Cron

```bash
sudo crontab -e -u www-data

# Add:
* * * * * cd /var/www/lms-sekolah && php artisan schedule:run >> /dev/null 2>&1
```

#### Step 11: Setup Firewall

```bash
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
sudo ufw status
```

---

## 📊 Post-Deployment Tasks

### 1. Create Admin User

```bash
php artisan tinker

App\Models\User::create([
    'name' => 'Administrator',
    'email' => 'admin@smksyasmu.sch.id',
    'password' => bcrypt('ChangeThisPassword123!'),
    'role' => 'admin'
]);
exit
```

### 2. Test Critical Features

- [ ] Login with admin credentials
- [ ] Create a test student
- [ ] Upload a test file
- [ ] Create a test assignment
- [ ] Test mobile responsive
- [ ] Test file download
- [ ] Test search/filter
- [ ] Test role permissions

### 3. Setup Monitoring

#### Error Tracking
```bash
# Check error logs
tail -f /var/www/lms-sekolah/storage/logs/laravel.log

# Nginx error logs
tail -f /var/log/nginx/error.log
```

#### Performance Monitoring
```bash
# Install htop for server monitoring
sudo apt install htop

# Monitor
htop
```

### 4. Backup Setup

Create backup script `/usr/local/bin/backup-lms.sh`:

```bash
#!/bin/bash
BACKUP_DIR="/backups/lms"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="lms_production"
DB_USER="lms_user"
DB_PASS="password"

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_$DATE.sql

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/lms-sekolah/storage/app/public

# Keep only last 7 days
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

echo "Backup completed: $DATE"
```

Make executable and schedule:
```bash
sudo chmod +x /usr/local/bin/backup-lms.sh
sudo crontab -e

# Add daily backup at 2 AM
0 2 * * * /usr/local/bin/backup-lms.sh >> /var/log/lms-backup.log 2>&1
```

---

## 🔄 Update/Rollback Procedures

### Update Application

```bash
# Backup first!
./backup-lms.sh

# Pull latest code
cd /var/www/lms-sekolah
git pull origin main

# Update dependencies
composer install --no-dev --optimize-autoloader
npm ci
npm run build

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo systemctl reload nginx
sudo systemctl reload php8.2-fpm
```

### Rollback

```bash
# Restore database
mysql -u lms_user -p lms_production < /backups/lms/db_YYYYMMDD.sql

# Restore files
tar -xzf /backups/lms/files_YYYYMMDD.tar.gz -C /

# Or git reset
git reset --hard <commit-hash>
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan config:cache
```

---

## 📈 Performance Optimization

### PHP-FPM Tuning

Edit `/etc/php/8.2/fpm/pool.d/www.conf`:

```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500
```

Restart PHP-FPM:
```bash
sudo systemctl restart php8.2-fpm
```

### Enable OPcache

Edit `/etc/php/8.2/fpm/php.ini`:

```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
```

### MySQL Optimization

Edit `/etc/mysql/mysql.conf.d/mysqld.cnf`:

```ini
[mysqld]
innodb_buffer_pool_size = 256M
innodb_log_file_size = 64M
max_connections = 100
```

Restart MySQL:
```bash
sudo systemctl restart mysql
```

---

## 🔍 Health Checks

### Daily Checks
```bash
# Check disk space
df -h

# Check database size
mysql -e "SELECT table_schema AS 'Database', ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)' FROM information_schema.TABLES GROUP BY table_schema;" -u root -p

# Check error logs
tail -100 /var/www/lms-sekolah/storage/logs/laravel.log

# Check Nginx access
tail -100 /var/log/nginx/access.log
```

### Weekly Checks
```bash
# Check SSL certificate expiry
sudo certbot certificates

# Check backup files
ls -lh /backups/lms/

# Update system packages
sudo apt update && sudo apt list --upgradable
```

---

## 📞 Emergency Contacts

**Server Issues:**
- Provider Support: [hosting provider]
- Server Admin: [admin contact]

**Application Issues:**
- Developer: [developer contact]
- Email: support@smksyasmu.sch.id

**Database Issues:**
- DBA: [database admin]
- Backup Location: /backups/lms/

---

## ✅ Deployment Checklist Summary

```
Pre-Deployment:
□ Code tested locally
□ .env.production prepared
□ Dependencies updated
□ Assets built (npm run build)
□ Database backup created
□ SSL certificate ready

Deployment:
□ Files uploaded
□ Composer install
□ npm install & build
□ .env configured
□ APP_KEY generated
□ Migrations run
□ Seeders run
□ Storage linked
□ Permissions set
□ Web server configured
□ SSL configured
□ Cron jobs set

Post-Deployment:
□ Admin user created
□ Test login
□ Test CRUD operations
□ Test file upload/download
□ Test mobile view
□ Test role permissions
□ Monitoring setup
□ Backup scheduled
□ Error logs checked
□ Performance verified

Documentation:
□ Server credentials saved
□ Database credentials saved
□ Admin credentials saved securely
□ Backup procedure documented
□ Update procedure documented
□ Emergency contacts updated
```

---

**Deployment Date:** _____________
**Deployed By:** _____________
**Server:** _____________
**Version:** 1.0.0

