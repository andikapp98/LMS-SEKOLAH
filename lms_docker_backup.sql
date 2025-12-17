--
-- PostgreSQL database dump
--

\restrict uhmxhSD5tEABfclYfye9GCV8tA8FYToFuxSZJEQXASfnjHllfnZtUEUwfyU64dw

-- Dumped from database version 15.15 (Debian 15.15-1.pgdg13+1)
-- Dumped by pg_dump version 15.15 (Debian 15.15-1.pgdg13+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: assignments; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.assignments (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    course character varying(255) NOT NULL,
    due_date date NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    created_by bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    course_id bigint,
    kelas json,
    file_path character varying(255),
    CONSTRAINT assignments_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'in_progress'::character varying, 'completed'::character varying])::text[])))
);


ALTER TABLE public.assignments OWNER TO laravel;

--
-- Name: COLUMN assignments.kelas; Type: COMMENT; Schema: public; Owner: laravel
--

COMMENT ON COLUMN public.assignments.kelas IS 'Kelas tujuan tugas (misal: X TKJ 1, XI RPL 2)';


--
-- Name: assignments_id_seq; Type: SEQUENCE; Schema: public; Owner: laravel
--

CREATE SEQUENCE public.assignments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.assignments_id_seq OWNER TO laravel;

--
-- Name: assignments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: laravel
--

ALTER SEQUENCE public.assignments_id_seq OWNED BY public.assignments.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO laravel;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO laravel;

--
-- Name: course_teacher; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.course_teacher (
    id bigint NOT NULL,
    course_id bigint NOT NULL,
    teacher_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.course_teacher OWNER TO laravel;

--
-- Name: course_teacher_id_seq; Type: SEQUENCE; Schema: public; Owner: laravel
--

CREATE SEQUENCE public.course_teacher_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.course_teacher_id_seq OWNER TO laravel;

--
-- Name: course_teacher_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: laravel
--

ALTER SEQUENCE public.course_teacher_id_seq OWNED BY public.course_teacher.id;


--
-- Name: courses; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.courses (
    id bigint NOT NULL,
    kode_mapel character varying(255) NOT NULL,
    nama_mapel character varying(255) NOT NULL,
    teacher_id bigint,
    kelas character varying(255),
    jam_per_minggu integer DEFAULT 2 NOT NULL,
    deskripsi text,
    semester character varying(255) DEFAULT 'ganjil'::character varying NOT NULL,
    tahun_ajaran character varying(255) DEFAULT '2025/2026'::character varying NOT NULL,
    status character varying(255) DEFAULT 'aktif'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT courses_semester_check CHECK (((semester)::text = ANY ((ARRAY['ganjil'::character varying, 'genap'::character varying])::text[]))),
    CONSTRAINT courses_status_check CHECK (((status)::text = ANY ((ARRAY['aktif'::character varying, 'non-aktif'::character varying])::text[])))
);


ALTER TABLE public.courses OWNER TO laravel;

--
-- Name: COLUMN courses.kode_mapel; Type: COMMENT; Schema: public; Owner: laravel
--

COMMENT ON COLUMN public.courses.kode_mapel IS 'Kode Mata Pelajaran';


--
-- Name: COLUMN courses.kelas; Type: COMMENT; Schema: public; Owner: laravel
--

COMMENT ON COLUMN public.courses.kelas IS 'Kelas yang diajar (contoh: 10 TPM 1)';


--
-- Name: courses_id_seq; Type: SEQUENCE; Schema: public; Owner: laravel
--

CREATE SEQUENCE public.courses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.courses_id_seq OWNER TO laravel;

--
-- Name: courses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: laravel
--

ALTER SEQUENCE public.courses_id_seq OWNED BY public.courses.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO laravel;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: laravel
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.failed_jobs_id_seq OWNER TO laravel;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: laravel
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO laravel;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO laravel;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: laravel
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.jobs_id_seq OWNER TO laravel;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: laravel
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: learning_media; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.learning_media (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    course_id bigint NOT NULL,
    uploaded_by bigint NOT NULL,
    type character varying(255) DEFAULT 'dokumen'::character varying NOT NULL,
    file_path character varying(255),
    file_name character varying(255),
    file_type character varying(255),
    file_size integer,
    url character varying(255),
    kelas json,
    visibility character varying(255) DEFAULT 'public'::character varying NOT NULL,
    download_count integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT learning_media_type_check CHECK (((type)::text = ANY ((ARRAY['modul'::character varying, 'video'::character varying, 'audio'::character varying, 'dokumen'::character varying, 'presentasi'::character varying, 'link'::character varying, 'lainnya'::character varying])::text[]))),
    CONSTRAINT learning_media_visibility_check CHECK (((visibility)::text = ANY ((ARRAY['public'::character varying, 'private'::character varying])::text[])))
);


ALTER TABLE public.learning_media OWNER TO laravel;

--
-- Name: learning_media_id_seq; Type: SEQUENCE; Schema: public; Owner: laravel
--

CREATE SEQUENCE public.learning_media_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.learning_media_id_seq OWNER TO laravel;

--
-- Name: learning_media_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: laravel
--

ALTER SEQUENCE public.learning_media_id_seq OWNED BY public.learning_media.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO laravel;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: laravel
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.migrations_id_seq OWNER TO laravel;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: laravel
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO laravel;

--
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name text NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.personal_access_tokens OWNER TO laravel;

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: laravel
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.personal_access_tokens_id_seq OWNER TO laravel;

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: laravel
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: questions; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.questions (
    id bigint NOT NULL,
    quiz_id bigint NOT NULL,
    type character varying(255) NOT NULL,
    question_text text NOT NULL,
    explanation text,
    points integer DEFAULT 1 NOT NULL,
    "order" integer DEFAULT 0 NOT NULL,
    options json,
    correct_answer text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT questions_type_check CHECK (((type)::text = ANY ((ARRAY['multiple_choice'::character varying, 'true_false'::character varying, 'short_answer'::character varying, 'essay'::character varying])::text[])))
);


ALTER TABLE public.questions OWNER TO laravel;

--
-- Name: COLUMN questions.explanation; Type: COMMENT; Schema: public; Owner: laravel
--

COMMENT ON COLUMN public.questions.explanation IS 'Feedback/explanation after answer';


--
-- Name: COLUMN questions.options; Type: COMMENT; Schema: public; Owner: laravel
--

COMMENT ON COLUMN public.questions.options IS 'For MCQ and True/False';


--
-- Name: questions_id_seq; Type: SEQUENCE; Schema: public; Owner: laravel
--

CREATE SEQUENCE public.questions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.questions_id_seq OWNER TO laravel;

--
-- Name: questions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: laravel
--

ALTER SEQUENCE public.questions_id_seq OWNED BY public.questions.id;


--
-- Name: quiz_attempts; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.quiz_attempts (
    id bigint NOT NULL,
    quiz_id bigint NOT NULL,
    student_id bigint NOT NULL,
    attempt_number integer DEFAULT 1 NOT NULL,
    started_at timestamp(0) without time zone NOT NULL,
    submitted_at timestamp(0) without time zone,
    score integer,
    points_earned integer,
    total_points integer,
    answers json NOT NULL,
    status character varying(255) DEFAULT 'in_progress'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT quiz_attempts_status_check CHECK (((status)::text = ANY ((ARRAY['in_progress'::character varying, 'submitted'::character varying, 'graded'::character varying])::text[])))
);


ALTER TABLE public.quiz_attempts OWNER TO laravel;

--
-- Name: COLUMN quiz_attempts.score; Type: COMMENT; Schema: public; Owner: laravel
--

COMMENT ON COLUMN public.quiz_attempts.score IS 'Score in percentage';


--
-- Name: COLUMN quiz_attempts.answers; Type: COMMENT; Schema: public; Owner: laravel
--

COMMENT ON COLUMN public.quiz_attempts.answers IS 'Student answers in JSON format';


--
-- Name: quiz_attempts_id_seq; Type: SEQUENCE; Schema: public; Owner: laravel
--

CREATE SEQUENCE public.quiz_attempts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.quiz_attempts_id_seq OWNER TO laravel;

--
-- Name: quiz_attempts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: laravel
--

ALTER SEQUENCE public.quiz_attempts_id_seq OWNED BY public.quiz_attempts.id;


--
-- Name: quizzes; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.quizzes (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    course_id bigint NOT NULL,
    created_by bigint NOT NULL,
    duration integer,
    max_attempts integer DEFAULT 1 NOT NULL,
    passing_score integer DEFAULT 70 NOT NULL,
    available_from timestamp(0) without time zone,
    available_until timestamp(0) without time zone,
    randomize_questions boolean DEFAULT false NOT NULL,
    show_correct_answers boolean DEFAULT true NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    kelas json,
    CONSTRAINT quizzes_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'published'::character varying, 'closed'::character varying])::text[])))
);


ALTER TABLE public.quizzes OWNER TO laravel;

--
-- Name: COLUMN quizzes.duration; Type: COMMENT; Schema: public; Owner: laravel
--

COMMENT ON COLUMN public.quizzes.duration IS 'Duration in minutes';


--
-- Name: COLUMN quizzes.passing_score; Type: COMMENT; Schema: public; Owner: laravel
--

COMMENT ON COLUMN public.quizzes.passing_score IS 'Passing score in percentage';


--
-- Name: COLUMN quizzes.kelas; Type: COMMENT; Schema: public; Owner: laravel
--

COMMENT ON COLUMN public.quizzes.kelas IS 'Target kelas untuk kuis';


--
-- Name: quizzes_id_seq; Type: SEQUENCE; Schema: public; Owner: laravel
--

CREATE SEQUENCE public.quizzes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.quizzes_id_seq OWNER TO laravel;

--
-- Name: quizzes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: laravel
--

ALTER SEQUENCE public.quizzes_id_seq OWNED BY public.quizzes.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO laravel;

--
-- Name: students; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.students (
    id bigint NOT NULL,
    nis character varying(255) NOT NULL,
    nisn character varying(255),
    nama character varying(255) NOT NULL,
    jenis_kelamin character varying(255) NOT NULL,
    tempat_lahir character varying(255),
    tanggal_lahir date,
    alamat text,
    kelas character varying(255),
    no_hp character varying(255),
    email character varying(255),
    nama_wali character varying(255),
    no_hp_wali character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT students_jenis_kelamin_check CHECK (((jenis_kelamin)::text = ANY ((ARRAY['L'::character varying, 'P'::character varying])::text[])))
);


ALTER TABLE public.students OWNER TO laravel;

--
-- Name: students_id_seq; Type: SEQUENCE; Schema: public; Owner: laravel
--

CREATE SEQUENCE public.students_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.students_id_seq OWNER TO laravel;

--
-- Name: students_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: laravel
--

ALTER SEQUENCE public.students_id_seq OWNED BY public.students.id;


--
-- Name: teachers; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.teachers (
    id bigint NOT NULL,
    nik character varying(255),
    nama character varying(255) NOT NULL,
    email character varying(255),
    no_hp character varying(255),
    mata_pelajaran character varying(255),
    status character varying(255) DEFAULT 'aktif'::character varying NOT NULL,
    alamat text,
    pendidikan_terakhir character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    kode_guru character varying(255),
    CONSTRAINT teachers_status_check CHECK (((status)::text = ANY ((ARRAY['aktif'::character varying, 'non-aktif'::character varying])::text[])))
);


ALTER TABLE public.teachers OWNER TO laravel;

--
-- Name: COLUMN teachers.mata_pelajaran; Type: COMMENT; Schema: public; Owner: laravel
--

COMMENT ON COLUMN public.teachers.mata_pelajaran IS 'Mata pelajaran yang diampu';


--
-- Name: COLUMN teachers.kode_guru; Type: COMMENT; Schema: public; Owner: laravel
--

COMMENT ON COLUMN public.teachers.kode_guru IS 'Kode Guru dari Excel';


--
-- Name: teachers_id_seq; Type: SEQUENCE; Schema: public; Owner: laravel
--

CREATE SEQUENCE public.teachers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.teachers_id_seq OWNER TO laravel;

--
-- Name: teachers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: laravel
--

ALTER SEQUENCE public.teachers_id_seq OWNED BY public.teachers.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: laravel
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    teacher_id bigint,
    role character varying(255) DEFAULT 'student'::character varying NOT NULL,
    CONSTRAINT users_role_check CHECK (((role)::text = ANY ((ARRAY['admin'::character varying, 'teacher'::character varying, 'student'::character varying])::text[])))
);


ALTER TABLE public.users OWNER TO laravel;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: laravel
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO laravel;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: laravel
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: assignments id; Type: DEFAULT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.assignments ALTER COLUMN id SET DEFAULT nextval('public.assignments_id_seq'::regclass);


--
-- Name: course_teacher id; Type: DEFAULT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.course_teacher ALTER COLUMN id SET DEFAULT nextval('public.course_teacher_id_seq'::regclass);


--
-- Name: courses id; Type: DEFAULT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.courses ALTER COLUMN id SET DEFAULT nextval('public.courses_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: learning_media id; Type: DEFAULT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.learning_media ALTER COLUMN id SET DEFAULT nextval('public.learning_media_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: questions id; Type: DEFAULT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.questions ALTER COLUMN id SET DEFAULT nextval('public.questions_id_seq'::regclass);


--
-- Name: quiz_attempts id; Type: DEFAULT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.quiz_attempts ALTER COLUMN id SET DEFAULT nextval('public.quiz_attempts_id_seq'::regclass);


--
-- Name: quizzes id; Type: DEFAULT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.quizzes ALTER COLUMN id SET DEFAULT nextval('public.quizzes_id_seq'::regclass);


--
-- Name: students id; Type: DEFAULT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.students ALTER COLUMN id SET DEFAULT nextval('public.students_id_seq'::regclass);


--
-- Name: teachers id; Type: DEFAULT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.teachers ALTER COLUMN id SET DEFAULT nextval('public.teachers_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: assignments; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.assignments (id, title, description, course, due_date, status, created_by, created_at, updated_at, course_id, kelas, file_path) FROM stdin;
1	Struktur Data	sadasd	Informatika	2025-12-31	pending	42	2025-12-06 04:15:15	2025-12-06 04:15:15	\N	["10 TPM 1"]	\N
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: course_teacher; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.course_teacher (id, course_id, teacher_id, created_at, updated_at) FROM stdin;
1	1000	166	2025-12-06 03:48:59	2025-12-06 03:48:59
2	1001	166	2025-12-06 03:48:59	2025-12-06 03:48:59
3	1002	167	2025-12-06 03:48:59	2025-12-06 03:48:59
4	1003	168	2025-12-06 03:48:59	2025-12-06 03:48:59
5	1004	169	2025-12-06 03:48:59	2025-12-06 03:48:59
6	1005	170	2025-12-06 03:48:59	2025-12-06 03:48:59
7	1006	171	2025-12-06 03:48:59	2025-12-06 03:48:59
8	1007	171	2025-12-06 03:48:59	2025-12-06 03:48:59
9	1008	172	2025-12-06 03:48:59	2025-12-06 03:48:59
10	1009	173	2025-12-06 03:48:59	2025-12-06 03:48:59
11	1010	174	2025-12-06 03:48:59	2025-12-06 03:48:59
12	1011	174	2025-12-06 03:48:59	2025-12-06 03:48:59
13	1012	174	2025-12-06 03:49:00	2025-12-06 03:49:00
14	1013	174	2025-12-06 03:49:00	2025-12-06 03:49:00
15	1014	175	2025-12-06 03:49:00	2025-12-06 03:49:00
16	1015	175	2025-12-06 03:49:00	2025-12-06 03:49:00
17	1016	175	2025-12-06 03:49:00	2025-12-06 03:49:00
18	1017	175	2025-12-06 03:49:00	2025-12-06 03:49:00
19	1018	176	2025-12-06 03:49:00	2025-12-06 03:49:00
20	1019	176	2025-12-06 03:49:00	2025-12-06 03:49:00
21	1020	177	2025-12-06 03:49:00	2025-12-06 03:49:00
22	1021	178	2025-12-06 03:49:00	2025-12-06 03:49:00
23	1022	179	2025-12-06 03:49:00	2025-12-06 03:49:00
24	1023	179	2025-12-06 03:49:00	2025-12-06 03:49:00
25	1024	179	2025-12-06 03:49:00	2025-12-06 03:49:00
26	1025	180	2025-12-06 03:49:00	2025-12-06 03:49:00
27	1026	180	2025-12-06 03:49:00	2025-12-06 03:49:00
28	1027	180	2025-12-06 03:49:00	2025-12-06 03:49:00
29	1028	180	2025-12-06 03:49:00	2025-12-06 03:49:00
30	1029	181	2025-12-06 03:49:00	2025-12-06 03:49:00
31	1030	181	2025-12-06 03:49:00	2025-12-06 03:49:00
32	1031	182	2025-12-06 03:49:00	2025-12-06 03:49:00
33	1032	183	2025-12-06 03:49:00	2025-12-06 03:49:00
34	1033	184	2025-12-06 03:49:00	2025-12-06 03:49:00
35	1034	184	2025-12-06 03:49:00	2025-12-06 03:49:00
36	1035	184	2025-12-06 03:49:00	2025-12-06 03:49:00
37	1036	184	2025-12-06 03:49:00	2025-12-06 03:49:00
38	1037	184	2025-12-06 03:49:00	2025-12-06 03:49:00
39	1038	184	2025-12-06 03:49:00	2025-12-06 03:49:00
40	1039	185	2025-12-06 03:49:00	2025-12-06 03:49:00
41	1040	186	2025-12-06 03:49:00	2025-12-06 03:49:00
42	1041	186	2025-12-06 03:49:00	2025-12-06 03:49:00
43	1042	187	2025-12-06 03:49:00	2025-12-06 03:49:00
44	1043	187	2025-12-06 03:49:00	2025-12-06 03:49:00
45	1044	187	2025-12-06 03:49:00	2025-12-06 03:49:00
46	1045	187	2025-12-06 03:49:00	2025-12-06 03:49:00
47	1046	188	2025-12-06 03:49:00	2025-12-06 03:49:00
48	1047	188	2025-12-06 03:49:00	2025-12-06 03:49:00
49	1048	188	2025-12-06 03:49:00	2025-12-06 03:49:00
50	1049	188	2025-12-06 03:49:00	2025-12-06 03:49:00
51	1050	189	2025-12-06 03:49:00	2025-12-06 03:49:00
52	1051	191	2025-12-06 03:49:00	2025-12-06 03:49:00
53	1052	191	2025-12-06 03:49:00	2025-12-06 03:49:00
54	1053	191	2025-12-06 03:49:00	2025-12-06 03:49:00
55	1054	191	2025-12-06 03:49:00	2025-12-06 03:49:00
56	1055	191	2025-12-06 03:49:00	2025-12-06 03:49:00
57	1056	191	2025-12-06 03:49:00	2025-12-06 03:49:00
58	1057	192	2025-12-06 03:49:00	2025-12-06 03:49:00
59	1058	192	2025-12-06 03:49:00	2025-12-06 03:49:00
60	1059	193	2025-12-06 03:49:00	2025-12-06 03:49:00
61	1060	193	2025-12-06 03:49:00	2025-12-06 03:49:00
62	1061	193	2025-12-06 03:49:00	2025-12-06 03:49:00
63	1062	193	2025-12-06 03:49:00	2025-12-06 03:49:00
64	1063	193	2025-12-06 03:49:00	2025-12-06 03:49:00
65	1064	194	2025-12-06 03:49:01	2025-12-06 03:49:01
66	1065	194	2025-12-06 03:49:01	2025-12-06 03:49:01
67	1066	194	2025-12-06 03:49:01	2025-12-06 03:49:01
68	1067	194	2025-12-06 03:49:01	2025-12-06 03:49:01
69	1068	194	2025-12-06 03:49:01	2025-12-06 03:49:01
70	1069	194	2025-12-06 03:49:01	2025-12-06 03:49:01
71	1070	195	2025-12-06 03:49:01	2025-12-06 03:49:01
72	1071	195	2025-12-06 03:49:01	2025-12-06 03:49:01
73	1072	196	2025-12-06 03:49:01	2025-12-06 03:49:01
74	1073	196	2025-12-06 03:49:01	2025-12-06 03:49:01
75	1074	196	2025-12-06 03:49:01	2025-12-06 03:49:01
76	1075	196	2025-12-06 03:49:01	2025-12-06 03:49:01
77	1076	196	2025-12-06 03:49:01	2025-12-06 03:49:01
78	1077	196	2025-12-06 03:49:01	2025-12-06 03:49:01
79	1078	197	2025-12-06 03:49:01	2025-12-06 03:49:01
80	1079	197	2025-12-06 03:49:01	2025-12-06 03:49:01
81	1080	197	2025-12-06 03:49:01	2025-12-06 03:49:01
82	1081	197	2025-12-06 03:49:01	2025-12-06 03:49:01
83	1082	198	2025-12-06 03:49:01	2025-12-06 03:49:01
84	1083	199	2025-12-06 03:49:01	2025-12-06 03:49:01
85	1084	199	2025-12-06 03:49:01	2025-12-06 03:49:01
86	1085	199	2025-12-06 03:49:01	2025-12-06 03:49:01
87	1086	200	2025-12-06 03:49:01	2025-12-06 03:49:01
88	1087	200	2025-12-06 03:49:01	2025-12-06 03:49:01
89	1088	201	2025-12-06 03:49:01	2025-12-06 03:49:01
90	1089	201	2025-12-06 03:49:01	2025-12-06 03:49:01
91	1090	202	2025-12-06 03:49:01	2025-12-06 03:49:01
92	1091	202	2025-12-06 03:49:01	2025-12-06 03:49:01
93	1092	202	2025-12-06 03:49:01	2025-12-06 03:49:01
94	1093	204	2025-12-06 03:49:01	2025-12-06 03:49:01
95	1094	204	2025-12-06 03:49:01	2025-12-06 03:49:01
96	1095	204	2025-12-06 03:49:01	2025-12-06 03:49:01
97	1096	204	2025-12-06 03:49:01	2025-12-06 03:49:01
98	1097	204	2025-12-06 03:49:01	2025-12-06 03:49:01
99	1098	205	2025-12-06 03:49:01	2025-12-06 03:49:01
100	1099	205	2025-12-06 03:49:01	2025-12-06 03:49:01
101	1100	205	2025-12-06 03:49:01	2025-12-06 03:49:01
102	1101	205	2025-12-06 03:49:01	2025-12-06 03:49:01
103	1102	205	2025-12-06 03:49:01	2025-12-06 03:49:01
104	1103	206	2025-12-06 03:49:01	2025-12-06 03:49:01
105	1104	206	2025-12-06 03:49:01	2025-12-06 03:49:01
106	1105	207	2025-12-06 03:49:01	2025-12-06 03:49:01
107	1106	208	2025-12-06 03:49:01	2025-12-06 03:49:01
108	1107	209	2025-12-06 03:49:01	2025-12-06 03:49:01
109	1108	209	2025-12-06 03:49:01	2025-12-06 03:49:01
110	1109	210	2025-12-06 03:49:01	2025-12-06 03:49:01
111	1110	211	2025-12-06 03:49:01	2025-12-06 03:49:01
112	1111	211	2025-12-06 03:49:01	2025-12-06 03:49:01
113	1112	211	2025-12-06 03:49:01	2025-12-06 03:49:01
114	1113	212	2025-12-06 03:49:01	2025-12-06 03:49:01
115	1114	212	2025-12-06 03:49:01	2025-12-06 03:49:01
116	1115	213	2025-12-06 03:49:01	2025-12-06 03:49:01
117	1116	213	2025-12-06 03:49:01	2025-12-06 03:49:01
118	1117	214	2025-12-06 03:49:01	2025-12-06 03:49:01
119	1118	214	2025-12-06 03:49:01	2025-12-06 03:49:01
120	1119	214	2025-12-06 03:49:02	2025-12-06 03:49:02
121	1120	214	2025-12-06 03:49:02	2025-12-06 03:49:02
\.


--
-- Data for Name: courses; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.courses (id, kode_mapel, nama_mapel, teacher_id, kelas, jam_per_minggu, deskripsi, semester, tahun_ajaran, status, created_at, updated_at) FROM stdin;
1010	PEMELIHARAAN_MESIN_KENDARAAN_R	Pemeliharaan Mesin Kendaraan Ringan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:48:59	2025-12-06 03:48:59
1011	PEMELIHARAAN_KELISTRIKAN_KENDA	Pemeliharaan Kelistrikan Kendaraan Ringan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:48:59	2025-12-06 03:48:59
1012	PROJEK_KREATIF_DAN_KEWIRAUSAHA	Projek Kreatif dan Kewirausahaan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:48:59	2025-12-06 03:48:59
1013	GAMBAR_TEKNIK_MANUFAKTUR	Gambar Teknik Manufaktur	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1014	IPAS	IPAS	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1015	BAHASA_JAWA	Bahasa Jawa	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1016	DASAR_KIMIA_ANALISIS_2	Dasar Kimia Analisis-2	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1017	ANALISIS_MIKROBIOLOGI	Analisis Mikrobiologi	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1018	TEKNIK_PEMESINAN_BUBUT_3	Teknik Pemesinan Bubut	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1019	TEKNIK_PEMESINAN_GERINDA	Teknik Pemesinan Gerinda	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1020	PEMELIHARAAN_SASIS_DAN_PEMINDA	Pemeliharaan Sasis dan Pemindah Tenaga Kendaraan Ringan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1021	IPAS_2	IPAS	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1022	MATEMATIKA_2	Matematika	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1023	INFORMATIKA	Informatika	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1024	PENGELOLAAN_SDM	Pengelolaan SDM	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1025	TEKNIK_PEMESINAN_BUBUT_4	Teknik Pemesinan Bubut	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1026	TEKNIK_PEMESINAN_GERINDA_2	Teknik Pemesinan Gerinda	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1027	TEKNIK_PENGELASAN	Teknik Pengelasan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1028	TEKNIK_MEKANIK_MESIN_INDUSTRI	Teknik Mekanik Mesin Industri	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1029	BAHASA_INDONESIA_2	Bahasa Indonesia	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1030	SEJARAH	Sejarah	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1031	DASAR_DASAR_TEKNIK_OTOMOTIF_2	Dasar-Dasar Teknik Otomotif-2	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1032	BAHASA_INGGRIS_DAN_BAHASA_ASIN_2	Bahasa Inggris dan Bahasa Asing Lainnya	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1033	DASAR_DASAR_DESAIN_KOMUNIKASI	Dasar-dasar Desain Komunikasi Visual-2	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1034	DASAR_DESAIN_DAN_KOMUNIKASI	Dasar Desain dan Komunikasi	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1035	KARYA_DESAIN	Karya Desain	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1036	PROSES_PRODUKSI_DESAIN	Proses Produksi Desain	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1037	PROJEK_KREATIF_DAN_KEWIRAUSAHA_2	Projek Kreatif dan Kewirausahaan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1038	ANIMASI	Animasi	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1039	AL_QURAN_HADITS	Al Quran Hadits	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1040	AQIDAH_AKHLAK_2	Aqidah Akhlak	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1041	PENDIDIKAN_PANCASILA_2	Pendidikan Pancasila	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1000	AQIDAH_AKHLAK	Aqidah Akhlak	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:48:59	2025-12-06 03:48:59
1001	DASAR_TEKNIK_MESIN_1	Dasar Teknik Mesin-1	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:48:59	2025-12-06 03:48:59
1002	BAHASA_INDONESIA	Bahasa Indonesia	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:48:59	2025-12-06 03:48:59
1003	MATEMATIKA	Matematika	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:48:59	2025-12-06 03:48:59
1004	FIQIH	Fiqih	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:48:59	2025-12-06 03:48:59
1005	BAHASA_INGGRIS_DAN_BAHASA_ASIN	Bahasa Inggris dan Bahasa Asing Lainnya	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:48:59	2025-12-06 03:48:59
1006	TEKNIK_PEMESINAN_FRAIS	Teknik Pemesinan Frais	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:48:59	2025-12-06 03:48:59
1007	TEKNIK_PEMESINAN_BUBUT	Teknik Pemesinan Bubut	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:48:59	2025-12-06 03:48:59
1008	PENDIDIKAN_PANCASILA	Pendidikan Pancasila	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:48:59	2025-12-06 03:48:59
1009	TEKNIK_PEMESINAN_BUBUT_2	Teknik Pemesinan Bubut	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:48:59	2025-12-06 03:48:59
1042	PEMELIHARAAN_SASIS_DAN_PEMINDA_2	Pemeliharaan Sasis dan Pemindah Tenaga Kendaraan Ringan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1043	TEKNIK_BODI_KENDARAAN_RINGAN	Teknik Bodi Kendaraan Ringan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1044	GAMBAR_TEKNIK_MANUFAKTUR_2	Gambar Teknik Manufaktur	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1045	TEKNIK_PEMESINAN_NONKONVENSION	Teknik Pemesinan Nonkonvensional	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1046	DASAR_MANAJEMEN_PERKANTORAN_DA	Dasar Manajemen Perkantoran dan Layanan Bisnis-2	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1047	KOMUNIKASI_KANTOR	Komunikasi Kantor	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1048	PENGELOLAAN_HUMAS_DAN_KEPROTOK	Pengelolaan Humas dan Keprotokolan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1049	AKUNTANSI_PERUSAHAAN_JASA	Akuntansi Perusahaan Jasa	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1050	PENDIDIKAN_JASMANI_OLAHRAGA	Pendidikan Jasmani, Olahraga, dan Kesehatan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1051	INFORMATIKA_2	Informatika	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1052	DASAR_DASAR_DESAIN_KOMUNIKASI_2	Dasar-Dasar Desain Komunikasi Visual-1	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1053	DESIGN_BRIEF	Design Brief	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1054	PROSES_PRODUKSI_DESAIN_2	Proses Produksi Desain	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1055	PROJEK_KREATIF_DAN_KEWIRAUSAHA_3	Projek Kreatif dan Kewirausahaan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1056	PRODUKSI_FILM	Produksi Film	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1057	SENI_BUDAYA	Seni Budaya	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1058	MATEMATIKA_3	Matematika	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1059	DASAR_MANAJEMEN_PERKANTORAN_DA_2	Dasar Manajemen Perkantoran dan Layanan Bisnis-1	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1060	PENGELOLAAN_KEARSIPAN	Pengelolaan Kearsipan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1061	BISNIS_DAN_KEUANGAN	Bisnis dan Keuangan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1062	AKUNTANSI_PERUSAHAAN_JASA_2	Akuntansi Perusahaan Jasa	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1063	BISNIS_DIGITAL	Bisnis Digital	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1064	IPAS_3	IPAS	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:00	2025-12-06 03:49:00
1065	DASAR_KIMIA_ANALISIS_1	Dasar Kimia Analisis-1	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1066	ANALISIS_KIMIA_INSTRUMEN	Analisis Kimia Instrumen	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1067	PROJEK_KREATIF_DAN_KEWIRAUSAHA_4	Projek Kreatif dan Kewirausahaan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1068	PENGOLAHAN_LIMBAH	Pengolahan Limbah	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1069	PENDIDIKAN_PANCASILA_3	Pendidikan Pancasila	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1070	PENDIDIKAN_JASMANI_OLAHRAGA_2	Pendidikan Jasmani, Olahraga, dan Kesehatan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1071	PENDIDIKAN_PANCASILA_4	Pendidikan Pancasila	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1072	IPAS_4	IPAS	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1073	ANALISIS_KUANTITATIF_KONVENSIO	Analisis Kuantitatif Konvensional	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1074	ANALISIS_PROKSIMAT	Analisis Proksimat	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1075	ANALISIS_KIMIA_INSTRUMEN_2	Analisis Kimia Instrumen	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1076	PROJEK_KREATIF_DAN_KEWIRAUSAHA_5	Projek Kreatif dan Kewirausahaan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1077	PROSES_INDUSTRI_KIMIA	Proses Industri Kimia	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1078	MATEMATIKA_4	Matematika	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1079	BISNIS_DIGITAL_2	Bisnis Digital	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1080	TEKNOLOGI_PERKANTORAN	Teknologi Perkantoran	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1081	BISNIS_DIGITAL_3	Bisnis Digital	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1082	AL_QURAN_HADITS_2	Al Quran Hadits	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1083	PROJEK_KREATIF_DAN_KEWIRAUSAHA_6	Projek Kreatif dan Kewirausahaan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1084	TEKNIK_PENGELASAN_2	Teknik Pengelasan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1085	TEKNIK_MEKANIK_MESIN_INDUSTRI_2	Teknik Mekanik Mesin Industri	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1086	TEKNIK_PEMESINAN_FRAIS_2	Teknik Pemesinan Frais	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1087	PROJEK_KREATIF_DAN_KEWIRAUSAHA_7	Projek Kreatif dan Kewirausahaan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1088	BAHASA_INDONESIA_3	Bahasa Indonesia	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1089	PROJEK_KREATIF_DAN_KEWIRAUSAHA_8	Projek Kreatif dan Kewirausahaan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1090	ADMINISTRASI_UMUM	Administrasi Umum	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1091	PENGELOLAAN_SARANA_DAN_PRASARA	Pengelolaan Sarana dan Prasarana	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1092	PROJEK_KREATIF_DAN_KEWIRAUSAHA_9	Projek Kreatif dan Kewirausahaan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1093	DASAR_TEKNIK_LOGISTIK_1	Dasar Teknik Logistik-1	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1094	TEKNIK_PENGADAAN_BARANG	Teknik Pengadaan Barang	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1095	PERDAGANGAN_INTERNASIONAL	Perdagangan Internasional	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1096	PROJEK_KREATIF_DAN_KEWIRAUSAHA_10	Projek Kreatif dan Kewirausahaan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1097	DASAR_TEKNIK_MESIN_2	Dasar Teknik Mesin-2	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1098	DASAR_TEKNIK_LOGISTIK_2	Dasar Teknik Logistik-2	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1099	SISTEM_PERGUDANGAN	Sistem Pergudangan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1100	TEKNIK_PENGIRIMAN_BARANG	Teknik Pengiriman Barang	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1101	SISTEM_INFORMASI_LOGISTIK	Sistem Informasi Logistik	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1102	BISNIS_RETAIL	Bisnis Retail	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1103	INFORMATIKA_3	Informatika	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1104	PERANGKAT_LUNAK_DESAIN	Perangkat Lunak desain	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1105	BAHASA_INGGRIS_DAN_BAHASA_ASIN_3	Bahasa inggris dan Bahasa Asing Lainnya	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1106	FIQIH_2	Fiqih	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1107	BAHASA_JEPANG	Bahasa Jepang	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1108	SEJARAH_2	Sejarah	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1109	TEKNIK_PEMESINAN_NONKONVENSION_2	Teknik Pemesinan Nonkonvensional	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1110	DASAR_DASAR_TEKNIK_OTOMOTIF_1	Dasar-Dasar Teknik Otomotif-1	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1111	CULTURE_INDUSTRY	Culture Industry	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1112	PROJEK_KREATIF_DAN_KEWIRAUSAHA_11	Projek Kreatif dan Kewirausahaan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1113	MATEMATIKA_5	Matematika	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1114	SEJARAH_3	Sejarah	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1115	DASAR_TEKNIK_MESIN_2_2	Dasar Teknik Mesin-2	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1116	PROJEK_KREATIF_DAN_KEWIRAUSAHA_12	Projek Kreatif dan Kewirausahaan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1117	PEMELIHARAAN_MESIN_KENDARAAN_R_2	Pemeliharaan Mesin Kendaraan Ringan	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1118	TEKNIK_SEPEDA_MOTOR	Teknik Sepeda Motor	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:01	2025-12-06 03:49:01
1119	CULTURE_INDUSTRY_2	Culture Industry	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:02	2025-12-06 03:49:02
1120	DASAR_TEKNIK_MESIN_1_2	Dasar Teknik Mesin-1	\N	\N	2	\N	ganjil	2025/2026	aktif	2025-12-06 03:49:02	2025-12-06 03:49:02
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: learning_media; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.learning_media (id, title, description, course_id, uploaded_by, type, file_path, file_name, file_type, file_size, url, kelas, visibility, download_count, created_at, updated_at) FROM stdin;
1	Pemrograman	\N	1103	42	presentasi	learning-materials/1765006779_Struktur Kurikulum TL_REVISI.pdf	1765006779_Struktur Kurikulum TL_REVISI.pdf	application/pdf	196906	\N	\N	public	1	2025-12-06 07:39:41	2025-12-06 08:21:12
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2025_11_28_014538_create_personal_access_tokens_table	1
5	2025_11_28_032335_create_assignments_table	1
7	2025_11_28_052525_create_students_table	2
8	2025_11_28_133121_create_teachers_table	3
9	2025_11_28_133119_create_courses_table	4
10	2025_11_28_134526_make_nip_nullable_in_teachers_table	4
11	2025_12_02_114801_remove_unique_from_email_in_teachers_table	4
12	2025_12_02_143654_rename_nip_to_nik_in_teachers_table	4
13	2025_12_03_021700_create_course_teacher_table	4
14	2025_12_03_021800_add_course_id_to_assignments_table	4
15	2025_12_03_021900_add_teacher_id_to_users_table	4
16	2025_12_03_022000_migrate_course_teacher_data	4
17	2025_12_04_050056_add_role_to_users_table	4
18	2025_12_04_050438_fix_teacher_id_foreign_key_in_users_table	4
19	2025_12_04_055259_add_kode_guru_to_teachers_table	4
20	2025_12_04_072115_fix_users_role_enum_values	4
21	2025_12_04_074041_add_kelas_to_assignments_table	4
22	2025_12_04_081654_change_kelas_to_json_in_assignments_table	4
23	2025_12_05_054920_create_learning_media_table	4
24	2025_12_06_041230_add_file_path_to_assignments_table	5
25	2025_12_06_072754_create_quizzes_table	6
26	2025_12_06_072805_create_questions_table	6
27	2025_12_06_072913_create_quiz_attempts_table	6
28	2025_12_09_080000_add_kelas_to_quizzes_table	7
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: questions; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.questions (id, quiz_id, type, question_text, explanation, points, "order", options, correct_answer, created_at, updated_at) FROM stdin;
3	1	multiple_choice	casdasda	\N	60	0	[{"text":"aSSA"},{"text":"ASX"},{"text":"Asa"},{"text":"asdda"}]	3	2025-12-09 02:16:02	2025-12-09 02:16:02
4	2	short_answer	rena : What is tyour name ?\nbagus: ...................Bagus	\N	50	0	\N	My name is	2025-12-09 09:36:58	2025-12-09 09:36:58
5	2	short_answer	Santoso : What do you think about Gen Z ?\nRetno : .......................................................................\n\nWhat expression does Retno give ?	\N	1	1	\N	I think	2025-12-09 09:36:58	2025-12-09 09:36:58
\.


--
-- Data for Name: quiz_attempts; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.quiz_attempts (id, quiz_id, student_id, attempt_number, started_at, submitted_at, score, points_earned, total_points, answers, status, created_at, updated_at) FROM stdin;
1	1	86	1	2025-12-09 09:29:02	2025-12-09 09:29:15	0	0	60	[null,null,null,2]	graded	2025-12-09 09:29:02	2025-12-09 09:29:15
2	2	86	1	2025-12-09 09:39:37	2025-12-09 09:40:03	100	51	51	[null,null,null,null,"my name is","i think"]	graded	2025-12-09 09:39:37	2025-12-09 09:40:03
3	2	86	2	2025-12-09 09:40:40	2025-12-09 09:41:03	100	51	51	[null,null,null,null,"my name is","i think"]	graded	2025-12-09 09:40:40	2025-12-09 09:41:03
\.


--
-- Data for Name: quizzes; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.quizzes (id, title, description, course_id, created_by, duration, max_attempts, passing_score, available_from, available_until, randomize_questions, show_correct_answers, status, created_at, updated_at, kelas) FROM stdin;
1	Program Koding	asasasa	1103	42	10	1	65	2025-12-09 09:00:00	2025-12-10 17:00:00	f	t	published	2025-12-09 01:51:52	2025-12-09 09:28:00	\N
2	fill the blank	read the dialogue below	1105	43	30	2	70	2025-12-09 09:33:00	2025-12-09 10:33:00	t	t	published	2025-12-09 09:36:58	2025-12-09 09:39:17	[]
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
VPcuvjuBvWQgoBRcjawPtpvuOq2DCQjRIwBQjjjV	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36	YToyOntzOjY6Il90b2tlbiI7czo0MDoieXo1WGxHSkZjckRtZFNrc2lLVzk0TXZJZ2x3TnlhNTN0em03Y0EzZSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==	1765257943
idjzzT4ChjniiGB2C2JclXXDnaLNycs5zIy3IvPJ	86	127.0.0.1	Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36 Edg/143.0.0.0	YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWmNhYXFVZERCejJTZUNuY3ZNNjZnSzJLUzRKSko1VEVkM01KckxlOCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI5OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvcXVpenplcyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjg2O30=	1765253496
\.


--
-- Data for Name: students; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.students (id, nis, nisn, nama, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat, kelas, no_hp, email, nama_wali, no_hp_wali, created_at, updated_at) FROM stdin;
1	5491/1008.009	\N	ACHMAD FATIKHAL	L	GRESIK	2010-04-17	JL. KH. IMAM SHODIQIN	10 TPM 1	0858785945507	\N	\N	\N	2025-12-05 07:27:29	2025-12-05 07:27:29
2	5492/1009.009	\N	ACHMAD HIDAYATULLAH	L	GRESIK	2010-07-09	JL KALIMAS BARU	10 TPM 1	088210173276	\N	\N	\N	2025-12-05 07:27:29	2025-12-05 07:27:29
3	5493/1010.009	\N	ACHMAD MULTAZAM ALKHAQIQI	L	GRESIK	2010-07-22	PEGANDEN	10 TPM 1	08981582003	\N	\N	\N	2025-12-05 07:27:29	2025-12-05 07:27:29
4	5494/1011.009	\N	ACHMAD RIECHAL UFUQILLAH	L	GRESIK	2010-08-12	BANYUTAMI	10 TPM 1	081217220902	\N	\N	\N	2025-12-05 07:27:29	2025-12-05 07:27:29
5	5495/1012.009	\N	AHMAD ARAFAT SAFRI	L	GRESIK	2010-01-17	JALAN NURUL JADID	10 TPM 1	085668988099	\N	\N	\N	2025-12-05 07:27:29	2025-12-05 07:27:29
6	5496/1013.009	\N	AHMAD FAIRUZ ASSHOB'RI	L	GRESIK	2010-03-22	PERUM BANJARSARI PERMAI G-10	10 TPM 1	085730894091	\N	\N	\N	2025-12-05 07:27:29	2025-12-05 07:27:29
7	5497/1014.009	\N	AHMAD RIF'AT KHUSNUL MA'AFI	L	LAMONGAN	2010-09-10	JL. KH. ALI ERFAN KEBON SARI	10 TPM 1	085806007427	\N	\N	\N	2025-12-05 07:27:29	2025-12-05 07:27:29
8	5498/1015.009	\N	AZRIEL HAPPY KAMAL	L	GRESIK	2010-07-05	JL MAKAM DALEM 8 NO 15	10 TPM 1	082229165963	\N	\N	\N	2025-12-05 07:27:29	2025-12-05 07:27:29
9	5499/1016.009	\N	DWI PERMANA PUTRA	L	GRESIK	2009-12-12	PEGANDEN	10 TPM 1	085258356938	\N	\N	\N	2025-12-05 07:27:29	2025-12-05 07:27:29
10	5500/1017.009	\N	DZAWIN NAGATAMA	L	GRESIK	2010-07-29	BETOYOGUCI	10 TPM 1	085646454146	\N	\N	\N	2025-12-05 07:27:29	2025-12-05 07:27:29
11	5501/1018.009	\N	ILHAM FIRZA PRATAMA	L	GRESIK	2010-07-19	PEGANDEN INDAH	10 TPM 1	082234793662	\N	\N	\N	2025-12-05 07:27:29	2025-12-05 07:27:29
12	5502/1019.009	\N	KEVIN RANGGA MAULANA	L	GRESIK	2009-12-03	JL. KYAI SAHLAN 17 RT 006 RW 002 MANYAR GRESIK	10 TPM 1	082137780903	\N	\N	\N	2025-12-05 07:27:29	2025-12-05 07:27:29
13	5503/1020.009	\N	MOCHAMMAD HAFIDZURROHMAN	L	GRESIK	2009-10-04	JL. HARUN THOHIR TKA 78	10 TPM 1	085815946605	\N	\N	\N	2025-12-05 07:27:29	2025-12-05 07:27:29
14	5504/1021.009	\N	MOH KHOIRUL ZIDAN ABIDIN	L	GRESIK	2009-12-29	JL. KYAI SAHLAN 33/28	10 TPM 1	0881026568669	\N	\N	\N	2025-12-05 07:27:29	2025-12-05 07:27:29
15	5505/1022.009	\N	MOHAMMAD CHARIR ROMADHONI	L	GRESIK	2010-09-02	JL. KYAI SAHLAN 19/27	10 TPM 1	081524875065	\N	\N	\N	2025-12-05 07:27:29	2025-12-05 07:27:29
16	5506/1023.009	\N	MOHAMMAD FARIS WIDYA TAMAKA	L	GRESIK	2010-02-03	RA. KARTINI VI/10A	10 TPM 1	085330975094	\N	\N	\N	2025-12-05 07:27:29	2025-12-05 07:27:29
17	5507/1024.009	\N	MOHAMMAD RIZKI MUBAROK	L	GRESIK	2007-04-28	KH.. SAHLAN 07 NO. 08	10 TPM 1	085646293138	\N	\N	\N	2025-12-05 07:27:30	2025-12-05 07:27:30
18	5508/1025.009	\N	MUCHAMMAD ASYROQ MURODY	L	GRESIK	2010-04-25	JL KYAI SAHLAN 12/04	10 TPM 1		\N	\N	\N	2025-12-05 07:27:30	2025-12-05 07:27:30
19	5509/1026.009	\N	MUHAMMAD AL FAIZ	L	GRESIK	2009-11-04	SUCI	10 TPM 1	085748484589	\N	\N	\N	2025-12-05 07:27:30	2025-12-05 07:27:30
20	5510/1027.009	\N	MUHAMMAD ALIF FAHMI ADITYA	L	GRESIK	2010-10-13	BETOYOGUCI	10 TPM 1	08155915371	\N	\N	\N	2025-12-05 07:27:30	2025-12-05 07:27:30
21	5511/1028.009	\N	MUHAMMAD DHANDY MAULANA	L	GRESIK	2010-02-16	MANGGA	10 TPM 1	085133381454	\N	\N	\N	2025-12-05 07:27:30	2025-12-05 07:27:30
22	5512/1029.009	\N	MUHAMMAD FAHRI RAMADHANI	L	GRESIK	2009-09-02	JL. KYAI SAHLAN 27/48B	10 TPM 1	0855859622792	\N	\N	\N	2025-12-05 07:27:30	2025-12-05 07:27:30
23	5513/1030.009	\N	MUHAMMAD FAHRUL AL AZMI	L	GRESIK	2009-06-06	JL SUMUR GONG GANG 1	10 TPM 1	088989411167	\N	\N	\N	2025-12-05 07:27:30	2025-12-05 07:27:30
24	5514/1031.009	\N	MUHAMMAD FAKHRI	L	GRESIK	2009-11-24	JL. MBAH WARENG NO. 54	10 TPM 1	083185141172	\N	\N	\N	2025-12-05 07:27:30	2025-12-05 07:27:30
25	5515/1032.009	\N	MUHAMMAD FARHAN	L	GRESIK	2010-04-18	JL. KEBON ASRI MAKAM PANJANG	10 TPM 1	083153457055	\N	\N	\N	2025-12-05 07:27:30	2025-12-05 07:27:30
26	5516/1033.009	\N	MUHAMMAD HABIBUR ROHMAN	L	GRESIK	2010-01-20	JL. KYAI SAHLAN 19	10 TPM 1	082132346065	\N	\N	\N	2025-12-05 07:27:30	2025-12-05 07:27:30
27	5517/1034.009	\N	MUHAMMAD LUCKY ARDIANSYAH	L	GRESIK	2010-07-27	PEGANDEN	10 TPM 1	083832705188	\N	\N	\N	2025-12-05 07:27:30	2025-12-05 07:27:30
28	5518/1035.009	\N	MUHAMMAD NURUSSOBAH	L	GRESIK	2010-03-07	JL KY SAHLAN 2/7	10 TPM 1	082331733832	\N	\N	\N	2025-12-05 07:27:30	2025-12-05 07:27:30
29	5519/1036.009	\N	MUHAMMAD PURNOMO ROHMAN	L	GRESIK	2010-10-18	KARANG REJO	10 TPM 1	081946773860	\N	\N	\N	2025-12-05 07:27:30	2025-12-05 07:27:30
30	5520/1037.009	\N	MUHAMMAD RIZKY MUBAROK	L	GRESIK	2009-11-16	JL. KYAI SAHLAN 23/41	10 TPM 1	082338147168	\N	\N	\N	2025-12-05 07:27:30	2025-12-05 07:27:30
31	5521/1038.009	\N	MUHAMMAD SHOFIL MUBARROD	L	GRESIK	2010-04-29	JALAN KEBON DALEM	10 TPM 1	08314134856	\N	\N	\N	2025-12-05 07:27:30	2025-12-05 07:27:30
32	5522/1039.009	\N	MUKHAMMAD AINUR KHABIBI	L	GRESIK	2010-03-03	MAKAM DALEM 65 A	10 TPM 1	081515503674	\N	\N	\N	2025-12-05 07:27:30	2025-12-05 07:27:30
33	5523/1040.009	\N	NUR ARIFANNY	L	LAMONGAN	2010-03-18	MAOR	10 TPM 1	085792711401	\N	\N	\N	2025-12-05 07:27:30	2025-12-05 07:27:30
34	5524/1041.009	\N	RIZQI MAULANAH	L	GRESIK	2010-01-02	DUSUN LAWO	10 TPM 1	087872314288	\N	\N	\N	2025-12-05 07:27:30	2025-12-05 07:27:30
\.


--
-- Data for Name: teachers; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.teachers (id, nik, nama, email, no_hp, mata_pelajaran, status, alamat, pendidikan_terakhir, created_at, updated_at, kode_guru) FROM stdin;
166	3525101903870002	Mohammad Arif, S.Pd.I.	arnaznews@gmail.com	085338584818	\N	aktif	Jl. Raden Joko Sungkono 5 No. 25 RT 003 RW 001 Peganden, Manyar - Gresik	\N	2025-12-05 15:28:49	2025-12-05 15:28:49	1
167	3525105704700004	Dra. Sri Anggrahitaningsih, M.M.	anggraitaningsih17@gmail.com	081231357775	\N	aktif	Jl. Bintan 37 GKB RT 01 RW 07 Yosowilangun, Manyar - Gresik	\N	2025-12-05 15:28:51	2025-12-05 15:28:51	2
168	3525140604680001	Drs. Sunarto	nartoyasmu@gmail.com	081332916786	\N	aktif	DR. Wahidin 20C No. 71 RT 002 RW 003 Randuagung, Kebomas - Gresik	\N	2025-12-05 15:28:51	2025-12-05 15:28:51	3
169	3525103107560001	H. Ainul Ma'arif, S.Pd.I.	mgsugmls@erapor-smk.net	085101874577	\N	aktif	Sukomulyo RT 002 RW 001 Manyar - Gresik	\N	2025-12-05 15:28:51	2025-12-05 15:28:51	4
170	3525106102740001	Nur Faizah, S.Pd.	nur.faizahh1974@gmail.com	082228079873	\N	aktif	Jl. Kyai Sahlan 23/05 Manyarsidorukun, Manyar - Gresik	\N	2025-12-05 15:28:51	2025-12-05 15:28:51	5
171	3525102602680001	H. Zainul Abidin, S.T.	bidinjaya1@gmail.com	085855314735	\N	aktif	Betoyo Guci RT 002 RW 004, Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	6
172	3525166411640122	Dra. Sa'adah	saadahibu5@gmail.com	081654922882	\N	aktif	Jl. Banjarbaru 28 GKB RT 003 RW 009 Sukomulyo, Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	7
173	3524142108710004	Mochamad Muis, S.Pd.	mochmuis300@gmail.com	081231342534	\N	aktif	Jl. Jamrud IV/34 PPS RT004 RW 014 Suci, Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	8
174	3525111112780001	Hamzah, S.Pd.	hamzah.ht11I2@gmail.com	081230996402	\N	aktif	Ds. Tambak Beras RT 01 RW 02, Cerme - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	9
175	3525055505790005	Rodlifah, S.Pd.	rodlifahn@gmail.com	085258242585	\N	aktif	Wadak Kidul RT 004 RW 001, Duduksampeyan - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	10
176	3525101006770006	Hadi Anas Mustofa, S.Pd.	hadianas100677@gmail.com	082141232450	\N	aktif	Jl. Embong Gumeno RT 019 RW 004 Sembayat Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	11
177	3525122104790003	Nurul Huda, S.Pd.	hudanurul21@gmail.com	081216983479	\N	aktif	Jl. Pasuruan RT 009 RW 002 Sukorejo Bungah - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	12
178	3525146907800001	Badriyatul Faizah, S.Si.	badriya2907@gmail.com	081332406459	\N	aktif	Jl. Ky. Sahlan 27 No. 6 Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	13
179	3525106012790001	Fitri Nuriana Susanti, S.Si.	fi2n.fitri@gmail.com	081330723706	\N	aktif	Jl. Palangkaraya I / 21 GKB Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	14
180	3515130710760006	Nor Hidayat, S.T.	rizqie.hidayat@gmail.com	085101178735	\N	aktif	Perum Peganden Villa Blok G No. 5 RT 011 RW 005 Peganden Manyar	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	15
181	3525104607820001	Hidayatul Mufidah, S.Pd.	mufidah825@gmail.com	085101244174	\N	aktif	Jl. Nongko Kerep Bungah Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	16
182	3525120909820001	Syamsul Wakhid, S.Pd.	wakhidsyamsul10@gmail.com	085704517254	\N	aktif	Jl. RA Kartini 3/3 No. 72 Sukorejo	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	17
183	3525106305830001	Isfatul Aini, S.S.	isfatulaini@gmail.com	085606792332	\N	aktif	Jl. Jaya 13 RT 017 RW 005 Sembayat Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	18
184	3525101004730010	Muhammad Syaifullah, A.Md.	syaifullah.aly@gmail.com	081334609714	\N	aktif	Jl. Telaga II No. 04 Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	19
185	3525104203750003	Siti Aisyah, S.Ag.	sitiaisyah02031975@gmail.com	081231820378	\N	aktif	Jl. Kyai Sahlan 23/06 Manyarsidorukun Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	20
186	3525105602810001	Azimatun Nikmah, S.Pd.I.	alyasaifud81@gmail.com	081331344639	\N	aktif	Sukomulyo RT 009 RW 002 Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	21
187	3501052505770003	Eko Setyobudi, S.T.	budidebby@gmail.com	081357156637	\N	aktif	Jl. Dr. Soetomo Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	22
188	3525142308810002	Rifqi Evel Abdullah, M.Pd.	evelrifqi@gmail.com	08121703859	\N	aktif	Jl. Yaqut Raya No. 28 PPS Suci Manyar	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	23
189	3525010404890002	Agus Dwi Kurniawan, S.Pd.	agusdwik@gmail.com	081232555537	\N	aktif	Ds. Sekargadung RT. 10 RW. 03 Dukun	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	24
190	3525121512790002	Misbahul Huda, S.Psi.	qorniu545@gmail.com	085104485131	\N	aktif	Maskumambang RT 011 RW 004 Bedanten Bungah - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	25
191	3525102210890003	Ilmi Rizky Hakiki, S.Pd.	ilmi.rizhaki@gmail.com	0811339913	\N	aktif	Jl. Besuki No. 55 GKB Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	26
192	3525105106880001	Linda Rahmawati Hidayah, S.Pd.	lind4hid4y4h@gmail.com	08563104913	\N	aktif	Jl. Simuntap No. 56 RT06 RW.03 Gumeno	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	27
193	3505166012910002	Ika Novitasari, S.Pd.	ika.novitasari94@gmail.com	081235656674	\N	aktif	Ds. Sumberkembar RT. 21 RW.06 Binangun, Blitar	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	28
194	3529164402920002	Deviana Ayu Kurnia, S.T.	devianaayu2@gmail.com	082335641665	\N	aktif	Jl. Kyai Sahlan V/7 Manyarejo	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	29
195	3525144304910002	Kartika Chandra Dewi, S.Or.	dewichandrakartika3@gmail.com	085649468868	\N	aktif	Jl. Dr. Wahidin SH 28 Baru Blok G No. 08 Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	30
196	3525105505920001	Hidayatul Munawaroh, S.Pd.	hidayatulmunawaroh99@gmail.com	085731462120	\N	aktif	Jl. Kyai Sahlan 25 No. 03 Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	31
197	3525105001920001	Citra Fudianita, S.Si.	fudianita.citra@gmail.com	085730313977	\N	aktif	Jl. Kyai Sahlan 21 No. 16 Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	32
198	3525125604870021	Ida Luthfiyyah, M.Pd.I.	idaluthfiyah99@gmail.com	085785439499	\N	aktif	Bungah RT 017 RW 006 Bungah - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	33
199	3525102711950001	Moh. Aslichul Chilmi	aslichulchilmi@gmail.com	081358858787	\N	aktif	Jl. Kyai Sahlan 20/29 RT 004 RW 001 Manyarsidorukun, Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	34
200	3525101112890002	Syah Nanda Hidayatullah, S.Pd.	syahnanda22@gmail.com	085730409313	\N	aktif	Jl. Raya Brantas No. 24 RT 004 RW 005 Randuagung, Kebomas - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	35
201	3525120905940005	Hendra Ristanto, M.Pd.	hendra09ristanto@gmail.com	085645316086	\N	aktif	Dsn Nongko Kerep RT 010 RW 004 Bungah - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	36
202	3525106009960001	Zuyyina Tamimiyah Firdausy, S.M.	Zuyyinatamimiyah@gmail.com	081332280887	\N	aktif	Jl. Kyai Sahlan 25 No. 16 Manyar Sidorukun Manyar	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	37
203	3525104206960002	Aulia Putri Fadmazati, S.Pd.	auliafadma23@gmail.com	085707341708	\N	aktif	Betoyo Kauman RT 007 RW 004 Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	38
204	3525101103930001	Moh. Alif Zukri Qomaruddin, S.T.	alifzukri@gmail.com	081230106144	\N	aktif	Jl. KH Ali Erfan RT 1 RW 1 No. 32 Banjarsari Manyar Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	39
205	3525100407960003	Mohammad Abidin, S.T.	abidinm3@gmail.com	081248927658	\N	aktif	Betoyo Kauman RT 007 RW 004 Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	40
206	3525101903040006	Andika Permana Putra, S.Kom.	andikapp1998@gmail.com	085730268625	\N	aktif	Betoyo Kauman RT 002 RW 001 Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	41
207	3525102502970001	Mohammad Shobachul Falach, S.Pd.	shobachulfalach@gmail.com	081515503556	\N	aktif	Jl. Kyai Sahlan 17 / 20 B RT 001 RW 001 Manyarsidorukun	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	42
208	3525101502980001	Sahlan Yahya, S.EI.	sahlan.yahya@yahoo.com	082291336376	\N	aktif	Jl. Makam Dalem RT 005 RW 002 Manyarejo Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	43
209	3525105612950004	Dini Denok Fatmawati, S.Pd.	dinidenox.fatmawati@gmail.com	082234795735	\N	aktif	Roomo No. 27 RT 004 RW 002 Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	44
210	3525120310870001	M. Rohmat, S.T.	m.rohmat578@gmail.com	085731735169	\N	aktif	Dusun Nongko Kerep No 25 RT 005 RW 002 Bungah - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	45
211	3525101105940003	A. Musthofa, S.T.	musthofahahmad@gmail.com	085328990269	\N	aktif	Suci RT 001 RW 001 Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	46
212	3525102208000001	Nagib Sholeh, S.Mat.	nasholeh208@gmail.com	085784531472	\N	aktif	Makam Dalem VI RT 005 RW 002 Manyarejo Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	47
213	3525101405920001	Muhammad Ainun Najib, S.T.	ainunnajibmuhammad14@gmail.com	087848680055	\N	aktif	Jl. Kyai Sahlan 21 No. 16 Manyar - Gresik	\N	2025-12-05 15:28:52	2025-12-05 15:28:52	48
214	3525090506030002	Muhammad Ady Ardiansyah	adypesek8@gmail.com	085850691682	\N	aktif	Kebon Duwur RT 001 RW 004 Desa Ngawen, Sidayu - Gresik	\N	2025-12-05 15:28:53	2025-12-05 15:28:53	49
215	3525102802000002	Ahmad Alfani Bayhaqie	fanabay000@gmail.com	082142660311	\N	aktif	Suci RT 001 RW 005 No. 14 Manyar - Gresik	\N	2025-12-05 15:28:53	2025-12-05 15:28:53	50
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: laravel
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, teacher_id, role) FROM stdin;
1	Administrator	admin@test.com	\N	$2y$12$VH3Ungzr0F.1KbYeYteQBuhf408ncRvxfUjwNG9Jf0ccYBzgf8HE6	\N	2025-12-05 11:47:38	2025-12-05 11:47:38	\N	admin
4	Drs. Sunarto	nartoyasmu@gmail.com	\N	$2y$12$lrpnGME7ZXY8irLGW3z7S..7FkRPpcSQQu1vkyVeZuQ4TMkWjSm2i	\N	2025-12-06 04:04:35	2025-12-06 04:09:41	168	teacher
5	H. Ainul Ma'arif, S.Pd.I.	mgsugmls@erapor-smk.net	\N	$2y$12$IvzEu1/pc3hdyZtqzl22m.n.1OtFFZHcJMiGvGPkxODzwVuEd58Nm	\N	2025-12-06 04:04:35	2025-12-06 04:09:41	169	teacher
6	Nur Faizah, S.Pd.	nur.faizahh1974@gmail.com	\N	$2y$12$3KLwVCTOn0PPoIYphewgiu5k7IBPunzSRHzk70HpSHxbDdKHGaDVy	\N	2025-12-06 04:04:35	2025-12-06 04:09:41	170	teacher
7	H. Zainul Abidin, S.T.	bidinjaya1@gmail.com	\N	$2y$12$vy05rs6X5yrztTXVGkPLg.HvTSov8/B.a033zcd4q16gxP5AX1xUO	\N	2025-12-06 04:04:35	2025-12-06 04:09:41	171	teacher
8	Dra. Sa'adah	saadahibu5@gmail.com	\N	$2y$12$R9uiUII10GLmwBYox9Qat.LI997mjE9P6QDIXCrekHb4fWDgEILEq	\N	2025-12-06 04:04:36	2025-12-06 04:09:41	172	teacher
9	Mochamad Muis, S.Pd.	mochmuis300@gmail.com	\N	$2y$12$4txbaIGNlmAXMQ75L0fULOq3KclAYt1G3/fDsHwHaN/AancLbg9GC	\N	2025-12-06 04:04:36	2025-12-06 04:09:41	173	teacher
10	Hamzah, S.Pd.	hamzah.ht11I2@gmail.com	\N	$2y$12$6uHgc6biUJ5dqHeWYk9Z9OEDTJCrdhUE1RX0yEg3eSpJbhJJxsPeS	\N	2025-12-06 04:04:36	2025-12-06 04:09:41	174	teacher
11	Rodlifah, S.Pd.	rodlifahn@gmail.com	\N	$2y$12$cnAtc2JDV/DLE8rwOXVjP.TkXfh/G8T6iGNVzEBCT1/EZ56SLVeDG	\N	2025-12-06 04:04:37	2025-12-06 04:09:41	175	teacher
13	Nurul Huda, S.Pd.	hudanurul21@gmail.com	\N	$2y$12$VZ4X2/xE3ut.l97MEs2bleKJ.tokaH.HEy9WvklIR98GRMEumsFiy	\N	2025-12-06 04:04:37	2025-12-06 04:09:41	177	teacher
14	Badriyatul Faizah, S.Si.	badriya2907@gmail.com	\N	$2y$12$G71LNLI5GSWD13IPubhosePQFJy5LSvrm0FG5B/gwy2I1SqDmIyIm	\N	2025-12-06 04:04:38	2025-12-06 04:09:41	178	teacher
15	Fitri Nuriana Susanti, S.Si.	fi2n.fitri@gmail.com	\N	$2y$12$hRgKov3FYCTwTQF9ztEJ5uOvyBc.T5y4TVPW8pncu.O/LccVMBVry	\N	2025-12-06 04:04:38	2025-12-06 04:09:41	179	teacher
16	Nor Hidayat, S.T.	rizqie.hidayat@gmail.com	\N	$2y$12$dKfbjlqVaZSC23yRorUxbO87FYOYTydaO7wZWxhyCTJLyNMg.x22S	\N	2025-12-06 04:04:38	2025-12-06 04:09:41	180	teacher
18	Syamsul Wakhid, S.Pd.	wakhidsyamsul10@gmail.com	\N	$2y$12$kA7L/RxAKsYqTJyiRUvdTOrvlzLmwN1oGEXroNT.BC/iFll5.5Etm	\N	2025-12-06 04:04:39	2025-12-06 04:09:41	182	teacher
19	Isfatul Aini, S.S.	isfatulaini@gmail.com	\N	$2y$12$HJFMaWoitv8BGSr.ntIs2Ovc/8hUwnLMzfGcGbnuUFB6686coZTU6	\N	2025-12-06 04:04:39	2025-12-06 04:09:41	183	teacher
20	Muhammad Syaifullah, A.Md.	syaifullah.aly@gmail.com	\N	$2y$12$IoMWBisKcMRp7B.hspXOTe6ECDmaxE5m0sCOjUTCrucSh29ks9Cue	\N	2025-12-06 04:04:39	2025-12-06 04:09:41	184	teacher
21	Siti Aisyah, S.Ag.	sitiaisyah02031975@gmail.com	\N	$2y$12$rU1Q8qsWHiCVJifojDHW/OD2L82OHlnqYhvZwDNhXEkZ3JeUQVTpu	\N	2025-12-06 04:04:40	2025-12-06 04:09:41	185	teacher
22	Azimatun Nikmah, S.Pd.I.	alyasaifud81@gmail.com	\N	$2y$12$X98ILo5DNoDX6GANi1nn/eWoUrI5JzXeRWdvyKEKsTvSA/.5naNom	\N	2025-12-06 04:04:40	2025-12-06 04:09:41	186	teacher
23	Eko Setyobudi, S.T.	budidebby@gmail.com	\N	$2y$12$DX4ABXM5usIWD/ZiP84swues4SVbcve.3UC0zl8ORcTZ6j3JfPsJi	\N	2025-12-06 04:04:40	2025-12-06 04:09:41	187	teacher
24	Rifqi Evel Abdullah, M.Pd.	evelrifqi@gmail.com	\N	$2y$12$uZvzwN3FpAvVz9oNwV8wMOV.ujr9gVRhzPj.I8GoVkssP2Q.0zjAO	\N	2025-12-06 04:04:40	2025-12-06 04:09:41	188	teacher
25	Agus Dwi Kurniawan, S.Pd.	agusdwik@gmail.com	\N	$2y$12$K3p9j/q3U.zyqsh/QTfLFOG12mQIQg0lLbCrRIELfX7l/LwTLnNhC	\N	2025-12-06 04:04:41	2025-12-06 04:09:41	189	teacher
26	Misbahul Huda, S.Psi.	qorniu545@gmail.com	\N	$2y$12$DhR2L3XP2LnvawkHKIKoo.Z3vR7f9piUVmG4/1/wH2w7c/1WPKaSW	\N	2025-12-06 04:04:41	2025-12-06 04:09:41	190	teacher
28	Linda Rahmawati Hidayah, S.Pd.	lind4hid4y4h@gmail.com	\N	$2y$12$Kgu4Dzz4IOM62NFtyr4iRe3Q9s.5Qdj6f8LQEFnmsSlAnDBQxIhh6	\N	2025-12-06 04:04:42	2025-12-06 04:09:41	192	teacher
29	Ika Novitasari, S.Pd.	ika.novitasari94@gmail.com	\N	$2y$12$22ev1MOwQIeJb18BdGE15OcNnn1CY4YGkJ/2QXEWCLNYcfERKR9JW	\N	2025-12-06 04:04:42	2025-12-06 04:09:41	193	teacher
30	Deviana Ayu Kurnia, S.T.	devianaayu2@gmail.com	\N	$2y$12$d20a0ByngMeom6dRkDa9zOJmL5k.Y8mWdPZ9Tn6Ftj.A/Jr9fWNDi	\N	2025-12-06 04:04:42	2025-12-06 04:09:41	194	teacher
31	Kartika Chandra Dewi, S.Or.	dewichandrakartika3@gmail.com	\N	$2y$12$QnfifpQAT6HcLp4wpZpXp.Q1KhrrRTRNw38Qf.HodzT6ECzxkGOm2	\N	2025-12-06 04:04:43	2025-12-06 04:09:41	195	teacher
32	Hidayatul Munawaroh, S.Pd.	hidayatulmunawaroh99@gmail.com	\N	$2y$12$1F9Mc/1lDUg4HMda7POZl.iGHDhlwEuIV2mzlCyR94H3YVsK4dCem	\N	2025-12-06 04:04:43	2025-12-06 04:09:41	196	teacher
33	Citra Fudianita, S.Si.	fudianita.citra@gmail.com	\N	$2y$12$N3G9yTZk93gQCk7EDtI86./dWLJDzP8pYefT/w7xUM2F6SDbCAaHa	\N	2025-12-06 04:04:43	2025-12-06 04:09:41	197	teacher
34	Ida Luthfiyyah, M.Pd.I.	idaluthfiyah99@gmail.com	\N	$2y$12$JEivGxbmQTZhIboMeOUsk..oiKLA3569NzDYx3CkwsWrHwIxnz3pS	\N	2025-12-06 04:04:44	2025-12-06 04:09:41	198	teacher
35	Moh. Aslichul Chilmi	aslichulchilmi@gmail.com	\N	$2y$12$LQ1Xgf85xYDFS7VfzC1LHeigDKPwCGn91bsM2FgXn68ar1BMx9/U2	\N	2025-12-06 04:04:44	2025-12-06 04:09:41	199	teacher
36	Syah Nanda Hidayatullah, S.Pd.	syahnanda22@gmail.com	\N	$2y$12$WWjG.GZqwsuZyJnteWc7P.Muilfjw0JhDnOeHsYM2EA0wTwjf570K	\N	2025-12-06 04:04:44	2025-12-06 04:09:41	200	teacher
37	Hendra Ristanto, M.Pd.	hendra09ristanto@gmail.com	\N	$2y$12$H06q5Z8ZfXTWocBzrYfvieP812Rkw8OV2jtvxsJwYhgcvPe8/WIUK	\N	2025-12-06 04:04:44	2025-12-06 04:09:41	201	teacher
39	Aulia Putri Fadmazati, S.Pd.	auliafadma23@gmail.com	\N	$2y$12$3gq4Lravb4AYpPyhyF0FMegNqWEsbpjOwbCEsYQY4z9S9p7ma/N2G	\N	2025-12-06 04:04:45	2025-12-06 04:09:41	203	teacher
40	Moh. Alif Zukri Qomaruddin, S.T.	alifzukri@gmail.com	\N	$2y$12$/lHdFxLORzHcXF9.MyM8hu7DKmNPZjemhIgk4.MLVx.03umn7b49O	\N	2025-12-06 04:04:45	2025-12-06 04:09:41	204	teacher
41	Mohammad Abidin, S.T.	abidinm3@gmail.com	\N	$2y$12$u19rIwOMQTpmeYSGnMtBN.CMmmJoepfIbdYxqpCSlL08lXLFNO50e	\N	2025-12-06 04:04:46	2025-12-06 04:09:41	205	teacher
43	Mohammad Shobachul Falach, S.Pd.	shobachulfalach@gmail.com	\N	$2y$12$7/Hbj03C0pX2kri/LyOZsOM1Gi26WjoCoReXmM9I9GMo8C/4er9bG	\N	2025-12-06 04:04:46	2025-12-06 04:09:41	207	teacher
44	Sahlan Yahya, S.EI.	sahlan.yahya@yahoo.com	\N	$2y$12$jaDOtjrakOby38k05IRIyOMkoIRPFO1fo2VsAac1vOrfKlkhOdQ/i	\N	2025-12-06 04:04:47	2025-12-06 04:09:41	208	teacher
45	Dini Denok Fatmawati, S.Pd.	dinidenox.fatmawati@gmail.com	\N	$2y$12$WsNMpog9s4T0XhtTo7arMu/Pbb3JyIKzQVaDA5i/RcRw9eQKHPjj2	\N	2025-12-06 04:04:47	2025-12-06 04:09:41	209	teacher
46	M. Rohmat, S.T.	m.rohmat578@gmail.com	\N	$2y$12$E55UN11uk4ChakRNyQvNaOessLPZYFZaoRTqYE7Oj8QVeE./XFdbe	\N	2025-12-06 04:04:47	2025-12-06 04:09:41	210	teacher
47	A. Musthofa, S.T.	musthofahahmad@gmail.com	\N	$2y$12$g/tgMHphTr.qdyvpLr50b.u/u7.3OsjNjl28VYR9afOYK4eKoadeO	\N	2025-12-06 04:04:48	2025-12-06 04:09:41	211	teacher
3	Dra. Sri Anggrahitaningsih, M.M.	anggraitaningsih17@gmail.com	\N	$2y$12$S5jM6ouj9P/Llfq5NPJbQup7aKJLglC6Ur3QJ81Q4AymwjxUB59Ua	\N	2025-12-06 04:04:34	2025-12-06 04:09:41	167	teacher
104	MUHAMMAD AL FAIZ	student55091026009@smksyasmu.sch.id	\N	$2y$12$eYcOanfAVAgRG1NCFkJ/m.vwyr9BEVt1AH0EoI/gZYqIBqc9.vSNC	\N	2025-12-06 08:02:59	2025-12-06 08:02:59	\N	student
105	MUHAMMAD ALIF FAHMI ADITYA	student55101027009@smksyasmu.sch.id	\N	$2y$12$GbdpwDBtQmyHsetejf11xutc/B3zzk8n.qOnQ0ZmAhmXaj4p0g2Xe	\N	2025-12-06 08:02:59	2025-12-06 08:02:59	\N	student
106	MUHAMMAD DHANDY MAULANA	student55111028009@smksyasmu.sch.id	\N	$2y$12$gIEmRQuHviZeJRipxiHItO9WdIApZWnyA8afEUQHePoOuGpaQZ00i	\N	2025-12-06 08:03:00	2025-12-06 08:03:00	\N	student
107	MUHAMMAD FAHRI RAMADHANI	student55121029009@smksyasmu.sch.id	\N	$2y$12$zvJ1UXdwoZBOULHHwtgxKeKmbwBJUDVg1XZ/90vBXraEPdKARqXuy	\N	2025-12-06 08:03:00	2025-12-06 08:03:00	\N	student
17	Hidayatul Mufidah, S.Pd.	mufidah825@gmail.com	\N	$2y$12$q0xpiyrJs09QXAyeNjer7es48xZMUTNPFYx1nmOrkFLHUf7i272LK	\N	2025-12-06 04:04:38	2025-12-06 04:09:41	181	teacher
38	Zuyyina Tamimiyah Firdausy, S.M.	Zuyyinatamimiyah@gmail.com	\N	$2y$12$rogqIM8IG6ox0hTsiGXR0eNgTYNZbPg0TNfdi4rAQud3OmdXNjVcu	\N	2025-12-06 04:04:45	2025-12-06 04:09:41	202	teacher
2	Mohammad Arif, S.Pd.I.	arnaznews@gmail.com	\N	$2y$12$.f2tNrTZSEpDC/7afIZuEO.4uVvzR9T/jOYTKAlNK5RO/29S/Ma7e	\N	2025-12-06 04:04:34	2025-12-06 04:09:41	166	teacher
12	Hadi Anas Mustofa, S.Pd.	hadianas100677@gmail.com	\N	$2y$12$VQDL/fhLwGrQvRdgS3zmN.91o/SsGxvK0mbNdAcBbyT8CNEygNGY.	\N	2025-12-06 04:04:37	2025-12-06 04:09:41	176	teacher
27	Ilmi Rizky Hakiki, S.Pd.	ilmi.rizhaki@gmail.com	\N	$2y$12$d7WFpAPUL06d3LKBvkZjLuy9ooASnaijBW8dIAYNVY3itM7y0PKA.	\N	2025-12-06 04:04:41	2025-12-06 04:09:41	191	teacher
42	Andika Permana Putra, S.Kom.	andikapp1998@gmail.com	\N	$2y$12$vgiXqyuYfkwOy1o7xadnkeYJiGHAW2..BGwzgjvQaQCTWi9oP.2QC	\N	2025-12-06 04:04:46	2025-12-06 04:09:41	206	teacher
48	Nagib Sholeh, S.Mat.	nasholeh208@gmail.com	\N	$2y$12$x45TmaR/p7saxJJIG724Cukk6r9m/SyneUkbUsLSoIVuOjCUpyZnC	\N	2025-12-06 04:04:48	2025-12-06 04:09:41	212	teacher
49	Muhammad Ainun Najib, S.T.	ainunnajibmuhammad14@gmail.com	\N	$2y$12$L0E5vX8ZZBkIjM9bgVsLm.6RkXbblDqSjE2m5NjoeuOLKtAB1q9eG	\N	2025-12-06 04:04:48	2025-12-06 04:09:41	213	teacher
50	Muhammad Ady Ardiansyah	adypesek8@gmail.com	\N	$2y$12$Q44rewGMV/kJt/WTVJzFjeDKi5sdX4LNOgDSupil.Qkm8fTxDgmYu	\N	2025-12-06 04:04:48	2025-12-06 04:09:42	214	teacher
51	Ahmad Alfani Bayhaqie	fanabay000@gmail.com	\N	$2y$12$yKKbAzTtNeG9BRYqddt0Nuvmr1.UuSuqbU8M0nJRKHso9giH79.sq	\N	2025-12-06 04:04:49	2025-12-06 04:09:42	215	teacher
86	ACHMAD FATIKHAL	student54911008009@smksyasmu.sch.id	\N	$2y$12$4PTobuLjtW9iFAf0fmD28efkW2W07gu2muawF8kYlwBOSb827e18W	\N	2025-12-06 08:02:54	2025-12-06 08:02:54	\N	student
87	ACHMAD HIDAYATULLAH	student54921009009@smksyasmu.sch.id	\N	$2y$12$VWw0FtJlydKgx9qB5X9g5.b6zHrWmH4Dt7SzgvxgzG6fh5yNtwq2a	\N	2025-12-06 08:02:55	2025-12-06 08:02:55	\N	student
88	ACHMAD MULTAZAM ALKHAQIQI	student54931010009@smksyasmu.sch.id	\N	$2y$12$/.m3j7aJGWG2tI8xxKIXYuV8TETElfXHGRT735NfgG8C/GX3nImfC	\N	2025-12-06 08:02:55	2025-12-06 08:02:55	\N	student
89	ACHMAD RIECHAL UFUQILLAH	student54941011009@smksyasmu.sch.id	\N	$2y$12$LCfWmMKpjzu0cxg.u2N2/OQQ3XyXAVNQ9rYWcda1aepaBz8x6RK.u	\N	2025-12-06 08:02:55	2025-12-06 08:02:55	\N	student
90	AHMAD ARAFAT SAFRI	student54951012009@smksyasmu.sch.id	\N	$2y$12$fWRt9FwefJZiOj.cu4vNcOTqf0wpcCjKR5zGoHcsw2sXfCmXMjQYG	\N	2025-12-06 08:02:56	2025-12-06 08:02:56	\N	student
91	AHMAD FAIRUZ ASSHOB'RI	student54961013009@smksyasmu.sch.id	\N	$2y$12$iegKHsGMEYiTncbxnEbnke/WE1qt58/Pms0zg5QxnqXENt3zViQdq	\N	2025-12-06 08:02:56	2025-12-06 08:02:56	\N	student
92	AHMAD RIF'AT KHUSNUL MA'AFI	student54971014009@smksyasmu.sch.id	\N	$2y$12$TuwhSzaDnEP1AJad4/pi3esehFBbWNUKRfqMIemiuZ4NtIr6CEgwy	\N	2025-12-06 08:02:56	2025-12-06 08:02:56	\N	student
93	AZRIEL HAPPY KAMAL	student54981015009@smksyasmu.sch.id	\N	$2y$12$Irb9Z6UJYmYZ9HG5/1oZ6O0k58gKX/Ig1cI1ApO7uy.WpmPwuAjRa	\N	2025-12-06 08:02:56	2025-12-06 08:02:56	\N	student
94	DWI PERMANA PUTRA	student54991016009@smksyasmu.sch.id	\N	$2y$12$mMSKnDeq7mUmJlLm9O53dOZaAvAigx2jtEh2kR99uVIhH7bw2cGlC	\N	2025-12-06 08:02:57	2025-12-06 08:02:57	\N	student
95	DZAWIN NAGATAMA	student55001017009@smksyasmu.sch.id	\N	$2y$12$jxeyVl3UszM25E9YgiMMh.GEnRzj0V4UJE6dL/k3oDhvgG4Kyq2eC	\N	2025-12-06 08:02:57	2025-12-06 08:02:57	\N	student
96	ILHAM FIRZA PRATAMA	student55011018009@smksyasmu.sch.id	\N	$2y$12$lg3bEbnINhJDANG0BfS5UeoC6PyDZnOfsmczy7qGb4R0FitCVNt56	\N	2025-12-06 08:02:57	2025-12-06 08:02:57	\N	student
97	KEVIN RANGGA MAULANA	student55021019009@smksyasmu.sch.id	\N	$2y$12$yw94vtaEpNj6mfrbmOtSGeh1SqxU9S5BQA93vfhO1AeRy15GbDly6	\N	2025-12-06 08:02:57	2025-12-06 08:02:57	\N	student
98	MOCHAMMAD HAFIDZURROHMAN	student55031020009@smksyasmu.sch.id	\N	$2y$12$XfJUuFaCtHLtWA1J2wK7n.SvzWDspGNl35i0LuyS7zVB.JQB/K8Um	\N	2025-12-06 08:02:58	2025-12-06 08:02:58	\N	student
99	MOH KHOIRUL ZIDAN ABIDIN	student55041021009@smksyasmu.sch.id	\N	$2y$12$LqPVuPothqG0DDjpMI3.T.AevbWqRfbZub28ma5ER6VSmNFtViHjS	\N	2025-12-06 08:02:58	2025-12-06 08:02:58	\N	student
100	MOHAMMAD CHARIR ROMADHONI	student55051022009@smksyasmu.sch.id	\N	$2y$12$xY5hBddeA3Bt9wSY8Mv13umRlUqKluuDsyxALdxP/X3HNajz4OK3e	\N	2025-12-06 08:02:58	2025-12-06 08:02:58	\N	student
101	MOHAMMAD FARIS WIDYA TAMAKA	student55061023009@smksyasmu.sch.id	\N	$2y$12$RGFzwTU9E3TTCm8Rk77xYeSjDdJ/rYWcmDP5BBlXoIh5s2zrCJR3u	\N	2025-12-06 08:02:58	2025-12-06 08:02:58	\N	student
102	MOHAMMAD RIZKI MUBAROK	student55071024009@smksyasmu.sch.id	\N	$2y$12$7zf1CJsfa.JcKuHPHjCczuTVppVRfckDfwjD3B505evxUJEIL6wfG	\N	2025-12-06 08:02:59	2025-12-06 08:02:59	\N	student
103	MUCHAMMAD ASYROQ MURODY	student55081025009@smksyasmu.sch.id	\N	$2y$12$dQQWF2sQKFSK.HYGWBhCReutL4exYqTMdV5YeW9ObquBpet5NhFqK	\N	2025-12-06 08:02:59	2025-12-06 08:02:59	\N	student
108	MUHAMMAD FAHRUL AL AZMI	student55131030009@smksyasmu.sch.id	\N	$2y$12$EdQnzeX5nvUJ47Tx8r7MFuPppBQMLkdtOhzrStAHhVN6kx1t8Ru42	\N	2025-12-06 08:03:00	2025-12-06 08:03:00	\N	student
109	MUHAMMAD FAKHRI	student55141031009@smksyasmu.sch.id	\N	$2y$12$6QfCZ2GYfs45xtDwIu18j.OuSfj0s5/E0ZBF5oHcQAoTXT19n.yOa	\N	2025-12-06 08:03:00	2025-12-06 08:03:00	\N	student
110	MUHAMMAD FARHAN	student55151032009@smksyasmu.sch.id	\N	$2y$12$9bC8Dw5.soxHsJAn/zI1F.zftG7L6.kQ701rUzl7lXipRQf1HkSe2	\N	2025-12-06 08:03:01	2025-12-06 08:03:01	\N	student
111	MUHAMMAD HABIBUR ROHMAN	student55161033009@smksyasmu.sch.id	\N	$2y$12$R07w9nXDIX9opH3O4tzFru61H1j3AGLzSi4whaB2ptmISua/bPg4a	\N	2025-12-06 08:03:01	2025-12-06 08:03:01	\N	student
112	MUHAMMAD LUCKY ARDIANSYAH	student55171034009@smksyasmu.sch.id	\N	$2y$12$RFG.TEIiPHjbJ.eBuZEtLerXsKn.zgkhKFFrKgdQjhfZcojCEZpuW	\N	2025-12-06 08:03:01	2025-12-06 08:03:01	\N	student
113	MUHAMMAD NURUSSOBAH	student55181035009@smksyasmu.sch.id	\N	$2y$12$S1MU872exFeL.zc2dadC2OBsqRfpT0OGJRrSr9x49gsAiDkr4ybyO	\N	2025-12-06 08:03:01	2025-12-06 08:03:01	\N	student
114	MUHAMMAD PURNOMO ROHMAN	student55191036009@smksyasmu.sch.id	\N	$2y$12$5aehD9DisiA9wwdcgdfxOuKnjxOPhAfbWoqoN15II/rHf/QeHm95u	\N	2025-12-06 08:03:02	2025-12-06 08:03:02	\N	student
115	MUHAMMAD RIZKY MUBAROK	student55201037009@smksyasmu.sch.id	\N	$2y$12$eb5zozhzu0CjNi2y4MBuCeCaJJfUtIkx7eJ4Vrt8pF5PNUjcSAi8m	\N	2025-12-06 08:03:02	2025-12-06 08:03:02	\N	student
116	MUHAMMAD SHOFIL MUBARROD	student55211038009@smksyasmu.sch.id	\N	$2y$12$NfX4jMrZnmcpzLA6o6tN6eabnDFL2eXFxJvR6nyjK7mRD8auQ8zrS	\N	2025-12-06 08:03:02	2025-12-06 08:03:02	\N	student
117	MUKHAMMAD AINUR KHABIBI	student55221039009@smksyasmu.sch.id	\N	$2y$12$O9kY3FO.C92DZWNDANnwTu0CAfu3IC2uOjF87E7Cikfpwbb6mV5WC	\N	2025-12-06 08:03:03	2025-12-06 08:03:03	\N	student
118	NUR ARIFANNY	student55231040009@smksyasmu.sch.id	\N	$2y$12$Cdfp7nAmgjwVsX0eDNjexOgC4F9NaZINyfS.6qtGu3VtaM0.VSLP6	\N	2025-12-06 08:03:03	2025-12-06 08:03:03	\N	student
119	RIZQI MAULANAH	student55241041009@smksyasmu.sch.id	\N	$2y$12$iY9HlTDL/epKU23SLY6/W.boRu0M6rBC4NAGzqblTnVOQJgX379ei	\N	2025-12-06 08:03:03	2025-12-06 08:03:03	\N	student
\.


--
-- Name: assignments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel
--

SELECT pg_catalog.setval('public.assignments_id_seq', 1, true);


--
-- Name: course_teacher_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel
--

SELECT pg_catalog.setval('public.course_teacher_id_seq', 121, true);


--
-- Name: courses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel
--

SELECT pg_catalog.setval('public.courses_id_seq', 1120, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: learning_media_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel
--

SELECT pg_catalog.setval('public.learning_media_id_seq', 1, true);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel
--

SELECT pg_catalog.setval('public.migrations_id_seq', 28, true);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, false);


--
-- Name: questions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel
--

SELECT pg_catalog.setval('public.questions_id_seq', 5, true);


--
-- Name: quiz_attempts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel
--

SELECT pg_catalog.setval('public.quiz_attempts_id_seq', 3, true);


--
-- Name: quizzes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel
--

SELECT pg_catalog.setval('public.quizzes_id_seq', 2, true);


--
-- Name: students_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel
--

SELECT pg_catalog.setval('public.students_id_seq', 34, true);


--
-- Name: teachers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel
--

SELECT pg_catalog.setval('public.teachers_id_seq', 222, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: laravel
--

SELECT pg_catalog.setval('public.users_id_seq', 119, true);


--
-- Name: assignments assignments_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.assignments
    ADD CONSTRAINT assignments_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: course_teacher course_teacher_course_id_teacher_id_unique; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.course_teacher
    ADD CONSTRAINT course_teacher_course_id_teacher_id_unique UNIQUE (course_id, teacher_id);


--
-- Name: course_teacher course_teacher_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.course_teacher
    ADD CONSTRAINT course_teacher_pkey PRIMARY KEY (id);


--
-- Name: courses courses_kode_mapel_unique; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_kode_mapel_unique UNIQUE (kode_mapel);


--
-- Name: courses courses_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: learning_media learning_media_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.learning_media
    ADD CONSTRAINT learning_media_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: questions questions_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.questions
    ADD CONSTRAINT questions_pkey PRIMARY KEY (id);


--
-- Name: quiz_attempts quiz_attempts_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.quiz_attempts
    ADD CONSTRAINT quiz_attempts_pkey PRIMARY KEY (id);


--
-- Name: quizzes quizzes_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.quizzes
    ADD CONSTRAINT quizzes_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: students students_email_unique; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_email_unique UNIQUE (email);


--
-- Name: students students_nis_unique; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_nis_unique UNIQUE (nis);


--
-- Name: students students_nisn_unique; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_nisn_unique UNIQUE (nisn);


--
-- Name: students students_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_pkey PRIMARY KEY (id);


--
-- Name: teachers teachers_kode_guru_unique; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.teachers
    ADD CONSTRAINT teachers_kode_guru_unique UNIQUE (kode_guru);


--
-- Name: teachers teachers_nip_unique; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.teachers
    ADD CONSTRAINT teachers_nip_unique UNIQUE (nik);


--
-- Name: teachers teachers_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.teachers
    ADD CONSTRAINT teachers_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: laravel
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: learning_media_course_id_index; Type: INDEX; Schema: public; Owner: laravel
--

CREATE INDEX learning_media_course_id_index ON public.learning_media USING btree (course_id);


--
-- Name: learning_media_type_index; Type: INDEX; Schema: public; Owner: laravel
--

CREATE INDEX learning_media_type_index ON public.learning_media USING btree (type);


--
-- Name: learning_media_uploaded_by_index; Type: INDEX; Schema: public; Owner: laravel
--

CREATE INDEX learning_media_uploaded_by_index ON public.learning_media USING btree (uploaded_by);


--
-- Name: personal_access_tokens_expires_at_index; Type: INDEX; Schema: public; Owner: laravel
--

CREATE INDEX personal_access_tokens_expires_at_index ON public.personal_access_tokens USING btree (expires_at);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: laravel
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: questions_quiz_id_index; Type: INDEX; Schema: public; Owner: laravel
--

CREATE INDEX questions_quiz_id_index ON public.questions USING btree (quiz_id);


--
-- Name: questions_type_index; Type: INDEX; Schema: public; Owner: laravel
--

CREATE INDEX questions_type_index ON public.questions USING btree (type);


--
-- Name: quiz_attempts_quiz_id_index; Type: INDEX; Schema: public; Owner: laravel
--

CREATE INDEX quiz_attempts_quiz_id_index ON public.quiz_attempts USING btree (quiz_id);


--
-- Name: quiz_attempts_quiz_id_student_id_index; Type: INDEX; Schema: public; Owner: laravel
--

CREATE INDEX quiz_attempts_quiz_id_student_id_index ON public.quiz_attempts USING btree (quiz_id, student_id);


--
-- Name: quiz_attempts_status_index; Type: INDEX; Schema: public; Owner: laravel
--

CREATE INDEX quiz_attempts_status_index ON public.quiz_attempts USING btree (status);


--
-- Name: quiz_attempts_student_id_index; Type: INDEX; Schema: public; Owner: laravel
--

CREATE INDEX quiz_attempts_student_id_index ON public.quiz_attempts USING btree (student_id);


--
-- Name: quizzes_course_id_index; Type: INDEX; Schema: public; Owner: laravel
--

CREATE INDEX quizzes_course_id_index ON public.quizzes USING btree (course_id);


--
-- Name: quizzes_created_by_index; Type: INDEX; Schema: public; Owner: laravel
--

CREATE INDEX quizzes_created_by_index ON public.quizzes USING btree (created_by);


--
-- Name: quizzes_status_index; Type: INDEX; Schema: public; Owner: laravel
--

CREATE INDEX quizzes_status_index ON public.quizzes USING btree (status);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: laravel
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: laravel
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: assignments assignments_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.assignments
    ADD CONSTRAINT assignments_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;


--
-- Name: assignments assignments_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.assignments
    ADD CONSTRAINT assignments_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: course_teacher course_teacher_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.course_teacher
    ADD CONSTRAINT course_teacher_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;


--
-- Name: course_teacher course_teacher_teacher_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.course_teacher
    ADD CONSTRAINT course_teacher_teacher_id_foreign FOREIGN KEY (teacher_id) REFERENCES public.teachers(id) ON DELETE CASCADE;


--
-- Name: courses courses_teacher_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_teacher_id_foreign FOREIGN KEY (teacher_id) REFERENCES public.teachers(id) ON DELETE SET NULL;


--
-- Name: learning_media learning_media_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.learning_media
    ADD CONSTRAINT learning_media_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;


--
-- Name: learning_media learning_media_uploaded_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.learning_media
    ADD CONSTRAINT learning_media_uploaded_by_foreign FOREIGN KEY (uploaded_by) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: questions questions_quiz_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.questions
    ADD CONSTRAINT questions_quiz_id_foreign FOREIGN KEY (quiz_id) REFERENCES public.quizzes(id) ON DELETE CASCADE;


--
-- Name: quiz_attempts quiz_attempts_quiz_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.quiz_attempts
    ADD CONSTRAINT quiz_attempts_quiz_id_foreign FOREIGN KEY (quiz_id) REFERENCES public.quizzes(id) ON DELETE CASCADE;


--
-- Name: quiz_attempts quiz_attempts_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.quiz_attempts
    ADD CONSTRAINT quiz_attempts_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: quizzes quizzes_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.quizzes
    ADD CONSTRAINT quizzes_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;


--
-- Name: quizzes quizzes_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.quizzes
    ADD CONSTRAINT quizzes_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: users users_teacher_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: laravel
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_teacher_id_foreign FOREIGN KEY (teacher_id) REFERENCES public.teachers(id) ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--

\unrestrict uhmxhSD5tEABfclYfye9GCV8tA8FYToFuxSZJEQXASfnjHllfnZtUEUwfyU64dw

