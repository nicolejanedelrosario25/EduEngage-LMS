CREATE DATABASE IF NOT EXISTS eduengage_lms
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE eduengage_lms;

CREATE TABLE users (
  user_id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id      VARCHAR(32)  NULL UNIQUE,
  username        VARCHAR(64)  NOT NULL UNIQUE,
  email           VARCHAR(255) NULL UNIQUE,
  password_hash   VARCHAR(255) NOT NULL,
  first_name      VARCHAR(100) NOT NULL,
  last_name       VARCHAR(100) NOT NULL,
  role            ENUM('student', 'instructor', 'admin') NOT NULL DEFAULT 'student',
  avatar_initials VARCHAR(4)   NULL,
  is_active       BOOLEAN      NOT NULL DEFAULT TRUE,
  created_at      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE categories (
  category_id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE courses (
  course_id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id     INT UNSIGNED NULL,
  instructor_id   INT UNSIGNED NOT NULL,
  title           VARCHAR(200) NOT NULL,
  description     TEXT NULL,
  lesson_count    INT UNSIGNED NOT NULL DEFAULT 0,
  duration_hours  INT UNSIGNED NOT NULL DEFAULT 0,
  module_count    INT UNSIGNED NOT NULL DEFAULT 0,
  icon_class      VARCHAR(80) NULL,
  color_theme     VARCHAR(30) NULL,
  status_label    VARCHAR(30) NULL,
  is_published    BOOLEAN NOT NULL DEFAULT TRUE,
  created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_courses_category
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL,
  CONSTRAINT fk_courses_instructor
    FOREIGN KEY (instructor_id) REFERENCES users(user_id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE modules (
  module_id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  course_id     INT UNSIGNED NOT NULL,
  title         VARCHAR(200) NOT NULL,
  sort_order    INT UNSIGNED NOT NULL DEFAULT 1,
  CONSTRAINT fk_modules_course
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE enrollments (
  enrollment_id      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id            INT UNSIGNED NOT NULL,
  course_id          INT UNSIGNED NOT NULL,
  progress_percent   DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  modules_completed  INT UNSIGNED NOT NULL DEFAULT 0,
  status_label       VARCHAR(30) NULL,
  enrolled_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_accessed_at   DATETIME NULL,
  UNIQUE KEY uk_enrollment (user_id, course_id),
  CONSTRAINT fk_enrollments_user
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  CONSTRAINT fk_enrollments_course
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE module_completions (
  completion_id  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  enrollment_id  INT UNSIGNED NOT NULL,
  module_id      INT UNSIGNED NOT NULL,
  completed_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_module_completion (enrollment_id, module_id),
  CONSTRAINT fk_completion_enrollment
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(enrollment_id) ON DELETE CASCADE,
  CONSTRAINT fk_completion_module
    FOREIGN KEY (module_id) REFERENCES modules(module_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE assignments (
  assignment_id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  course_id       INT UNSIGNED NOT NULL,
  title           VARCHAR(200) NOT NULL,
  description     TEXT NULL,
  due_at          DATETIME NOT NULL,
  max_points      INT UNSIGNED NULL DEFAULT 100,
  assignment_type ENUM('file', 'quiz', 'project', 'other') NOT NULL DEFAULT 'file',
  created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_assignments_course
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE assignment_submissions (
  submission_id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  assignment_id   INT UNSIGNED NOT NULL,
  user_id         INT UNSIGNED NOT NULL,
  status          ENUM('pending', 'submitted', 'graded', 'missing', 'late') NOT NULL DEFAULT 'pending',
  file_path       VARCHAR(500) NULL,
  notes           TEXT NULL,
  grade           DECIMAL(5,2) NULL,
  submitted_at    DATETIME NULL,
  graded_at       DATETIME NULL,
  UNIQUE KEY uk_submission (assignment_id, user_id),
  CONSTRAINT fk_submissions_assignment
    FOREIGN KEY (assignment_id) REFERENCES assignments(assignment_id) ON DELETE CASCADE,
  CONSTRAINT fk_submissions_user
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE quiz_questions (
  question_id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  assignment_id  INT UNSIGNED NOT NULL,
  question_text  TEXT NOT NULL,
  question_type  ENUM('multiple_choice', 'true_false', 'short_answer') NOT NULL DEFAULT 'multiple_choice',
  points         INT UNSIGNED NOT NULL DEFAULT 1,
  sort_order     INT UNSIGNED NOT NULL DEFAULT 1,
  CONSTRAINT fk_questions_assignment
    FOREIGN KEY (assignment_id) REFERENCES assignments(assignment_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE quiz_options (
  option_id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  question_id  INT UNSIGNED NOT NULL,
  option_text  VARCHAR(500) NOT NULL,
  is_correct   BOOLEAN NOT NULL DEFAULT FALSE,
  sort_order   INT UNSIGNED NOT NULL DEFAULT 1,
  CONSTRAINT fk_options_question
    FOREIGN KEY (question_id) REFERENCES quiz_questions(question_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE quiz_answers (
  answer_id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  submission_id      INT UNSIGNED NOT NULL,
  question_id        INT UNSIGNED NOT NULL,
  selected_option_id INT UNSIGNED NULL,
  short_answer_text  TEXT NULL,
  is_correct         BOOLEAN NULL,
  points_awarded     DECIMAL(5,2) NULL,
  answered_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_answer (submission_id, question_id),
  CONSTRAINT fk_answers_submission
    FOREIGN KEY (submission_id) REFERENCES assignment_submissions(submission_id) ON DELETE CASCADE,
  CONSTRAINT fk_answers_question
    FOREIGN KEY (question_id) REFERENCES quiz_questions(question_id) ON DELETE CASCADE,
  CONSTRAINT fk_answers_option
    FOREIGN KEY (selected_option_id) REFERENCES quiz_options(option_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE discussion_topics (
  topic_id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(80) NOT NULL UNIQUE,
  color_class VARCHAR(30) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE discussion_posts (
  post_id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  course_id     INT UNSIGNED NULL,
  user_id       INT UNSIGNED NOT NULL,
  topic_id      INT UNSIGNED NULL,
  title         VARCHAR(255) NOT NULL,
  body          TEXT NOT NULL,
  like_count    INT UNSIGNED NOT NULL DEFAULT 0,
  reply_count   INT UNSIGNED NOT NULL DEFAULT 0,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_posts_course
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE SET NULL,
  CONSTRAINT fk_posts_user
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  CONSTRAINT fk_posts_topic
    FOREIGN KEY (topic_id) REFERENCES discussion_topics(topic_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE discussion_replies (
  reply_id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  post_id     INT UNSIGNED NOT NULL,
  user_id     INT UNSIGNED NOT NULL,
  body        TEXT NOT NULL,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_replies_post
    FOREIGN KEY (post_id) REFERENCES discussion_posts(post_id) ON DELETE CASCADE,
  CONSTRAINT fk_replies_user
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE discussion_likes (
  like_id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  post_id     INT UNSIGNED NOT NULL,
  user_id     INT UNSIGNED NOT NULL,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_post_like (post_id, user_id),
  CONSTRAINT fk_likes_post
    FOREIGN KEY (post_id) REFERENCES discussion_posts(post_id) ON DELETE CASCADE,
  CONSTRAINT fk_likes_user
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE conversations (
  conversation_id  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE conversation_participants (
  participant_id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  conversation_id   INT UNSIGNED NOT NULL,
  user_id           INT UNSIGNED NOT NULL,
  last_read_at      DATETIME NULL,
  UNIQUE KEY uk_conv_user (conversation_id, user_id),
  CONSTRAINT fk_participants_conversation
    FOREIGN KEY (conversation_id) REFERENCES conversations(conversation_id) ON DELETE CASCADE,
  CONSTRAINT fk_participants_user
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE messages (
  message_id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  conversation_id  INT UNSIGNED NOT NULL,
  sender_id        INT UNSIGNED NOT NULL,
  body             TEXT NOT NULL,
  attachment_path  VARCHAR(500) NULL,
  sent_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  is_read          BOOLEAN NOT NULL DEFAULT FALSE,
  CONSTRAINT fk_messages_conversation
    FOREIGN KEY (conversation_id) REFERENCES conversations(conversation_id) ON DELETE CASCADE,
  CONSTRAINT fk_messages_sender
    FOREIGN KEY (sender_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE calendar_events (
  event_id      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  course_id     INT UNSIGNED NULL,
  assignment_id INT UNSIGNED NULL,
  title         VARCHAR(200) NOT NULL,
  description   TEXT NULL,
  event_type    ENUM('deadline', 'class', 'presentation', 'discussion', 'reminder', 'other')
                NOT NULL DEFAULT 'other',
  starts_at     DATETIME NOT NULL,
  ends_at       DATETIME NULL,
  created_by    INT UNSIGNED NULL,
  CONSTRAINT fk_events_course
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE SET NULL,
  CONSTRAINT fk_events_assignment
    FOREIGN KEY (assignment_id) REFERENCES assignments(assignment_id) ON DELETE SET NULL,
  CONSTRAINT fk_events_creator
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE announcements (
  announcement_id  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  course_id        INT UNSIGNED NULL,
  title            VARCHAR(255) NOT NULL,
  body             TEXT NULL,
  posted_at        DATE NOT NULL,
  created_by       INT UNSIGNED NULL,
  CONSTRAINT fk_announcements_course
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
  CONSTRAINT fk_announcements_creator
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE activity_logs (
  log_id        BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       INT UNSIGNED NOT NULL,
  activity_type ENUM(
    'login', 'logout', 'course_view', 'assignment_submit',
    'discussion_post', 'discussion_reply', 'message_sent', 'module_complete'
  ) NOT NULL,
  reference_id  INT UNSIGNED NULL,
  metadata      JSON NULL,
  logged_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_activity_user
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  INDEX idx_activity_user_date (user_id, logged_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE study_sessions (
  session_id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       INT UNSIGNED NOT NULL,
  started_at    DATETIME NOT NULL,
  ended_at      DATETIME NULL,
  duration_mins INT UNSIGNED NULL,
  CONSTRAINT fk_sessions_user
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_assignments_due ON assignments(due_at);
CREATE INDEX idx_submissions_status ON assignment_submissions(status);
CREATE INDEX idx_posts_created ON discussion_posts(created_at);
CREATE INDEX idx_messages_sent ON messages(sent_at);
CREATE INDEX idx_events_starts ON calendar_events(starts_at);

-- ------------------------------------------------------------
-- SAMPLE DATA
-- ------------------------------------------------------------

INSERT INTO users (student_id, username, email, password_hash, first_name, last_name, role, avatar_initials) VALUES
('2024-001', 'npeter', 'nathalie.peter@school.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nathalie Faye', 'Peter', 'student', 'NP'),
(NULL, 'jpetralba', 'josephine.petralba@school.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Josephine', 'Petralba', 'instructor', 'JP'),
(NULL, 'rbandalan', 'roderick.bandalan@school.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Roderick', 'Bandalan', 'instructor', 'RB'),
(NULL, 'wjoe', 'william.joe@school.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'William', 'Joe', 'instructor', 'WJ'),
('2024-002', 'ndelrosario', 'nicole.delrosario@school.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nicole Jane', 'Del Rosario', 'student', 'ND'),
('2024-003', 'vbalista', 'vincent.balista@school.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Vincent Jorge', 'Balista', 'student', 'VB'),
(NULL, 'acruz', 'ana.cruz@school.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ana', 'Cruz', 'instructor', 'AC');

INSERT INTO categories (name) VALUES
('Programming'), ('Database'), ('Research'), ('Design');

INSERT INTO courses (category_id, instructor_id, title, lesson_count, duration_hours, module_count, icon_class, color_theme, status_label) VALUES
(1, 3, 'Web Application Development', 15, 40, 15, 'fa-code', 'orange-round', 'In Progress'),
(1, 2, 'Systems Integration', 14, 36, 14, 'fa-server', 'purple-round', 'In Progress'),
(1, 2, 'Fundamentals of Data Analytics', 20, 45, 20, 'fa-globe', 'blue-round', 'Almost Done'),
(2, 4, 'Database Management', 14, 38, 14, 'fa-database', 'green-round', 'In Progress'),
(3, 2, 'Research Methods', 12, 30, 12, 'fa-flask', 'teal-round', 'In Progress'),
(4, 7, 'UI Design Activity', 10, 25, 10, 'fa-palette', 'pink-round', 'New Course');

INSERT INTO enrollments (user_id, course_id, progress_percent, modules_completed, status_label) VALUES
(1, 1, 78.00, 12, 'In Progress'),
(1, 2, 65.00, 9, 'In Progress'),
(1, 3, 90.00, 18, 'Almost Done'),
(1, 4, 72.00, 10, 'In Progress'),
(1, 5, 55.00, 6, 'In Progress'),
(1, 6, 34.00, 3, 'New Course');

INSERT INTO assignments (course_id, title, description, due_at, assignment_type) VALUES
(2, 'Research Proposal', 'Create the initial proposal for the LMS A/B Testing research study.', '2026-05-18 23:59:00', 'project'),
(1, 'UI Wireframe Design', 'Design the improved LMS dashboard wireframe using Figma.', '2026-05-20 23:59:00', 'file'),
(4, 'Database Quiz', 'Answer SQL, tables, relationships, and normalization questions.', '2026-05-22 23:59:00', 'quiz'),
(1, 'PHP Prototype Activity', 'Create a PHP LMS prototype based on the research improvement.', '2026-05-24 23:59:00', 'project');

INSERT INTO assignment_submissions (assignment_id, user_id, status, submitted_at) VALUES
(1, 1, 'pending', NULL),
(2, 1, 'submitted', '2026-05-15 14:30:00'),
(3, 1, 'pending', NULL),
(4, 1, 'submitted', '2026-05-10 09:00:00');

INSERT INTO quiz_questions (assignment_id, question_text, question_type, points, sort_order) VALUES
(3, 'Which SQL keyword is used to retrieve data from a database table?', 'multiple_choice', 1, 1),
(3, 'A primary key column can contain NULL values.', 'true_false', 1, 2),
(3, 'Which type of relationship typically requires a junction (bridge) table?', 'multiple_choice', 1, 3),
(3, 'What is the primary goal of database normalization?', 'multiple_choice', 1, 4),
(3, 'Briefly explain the difference between an INNER JOIN and a LEFT JOIN.', 'short_answer', 2, 5);

INSERT INTO quiz_options (question_id, option_text, is_correct, sort_order) VALUES
(1, 'SELECT', TRUE,  1),
(1, 'FETCH',  FALSE, 2),
(1, 'GET',    FALSE, 3),
(1, 'PULL',   FALSE, 4),
(2, 'True',   FALSE, 1),
(2, 'False',  TRUE,  2),
(3, 'One-to-one',       FALSE, 1),
(3, 'One-to-many',      FALSE, 2),
(3, 'Many-to-many',     TRUE,  3),
(3, 'Self-referencing', FALSE, 4),
(4, 'Increase data redundancy',                       FALSE, 1),
(4, 'Reduce data redundancy and improve integrity',   TRUE,  2),
(4, 'Speed up SELECT queries only',                   FALSE, 3),
(4, 'Allow more NULL values in columns',              FALSE, 4);

INSERT INTO discussion_topics (name, color_class) VALUES
('UI Design', 'green-topic'),
('LMS Features', 'blue-topic');

INSERT INTO discussion_posts (user_id, topic_id, title, body, like_count, reply_count, created_at) VALUES
(4, 1, 'How can UI design improve student engagement?',
 'A clean dashboard can help students easily find assignments, deadlines, announcements, and course materials without confusion.',
 24, 5, NOW() - INTERVAL 2 HOUR),
(1, 2, 'Which LMS feature is most useful for students?',
 'I think progress tracking is one of the best features because students can monitor their completion and performance in real time.',
 18, 3, NOW() - INTERVAL 1 DAY);

INSERT INTO announcements (title, body, posted_at) VALUES
('Midterm presentation schedule has been posted.', NULL, '2026-05-12'),
('Submit UI wireframes before Friday.', NULL, '2026-05-16');

INSERT INTO calendar_events (course_id, assignment_id, title, event_type, starts_at) VALUES
(NULL, NULL, 'Midterm Presentation', 'presentation', '2026-05-12 09:00:00'),
(2, 1, 'Research Proposal', 'deadline', '2026-05-18 23:59:00'),
(1, 2, 'UI Wireframe', 'deadline', '2026-05-20 23:59:00'),
(4, 3, 'Database Quiz', 'deadline', '2026-05-22 23:59:00');

INSERT INTO conversations () VALUES ();

INSERT INTO conversation_participants (conversation_id, user_id) VALUES
(1, 1),
(1, 2);

INSERT INTO messages (conversation_id, sender_id, body, sent_at) VALUES
(1, 2, 'Please check the updated instruction for your research proposal.', NOW() - INTERVAL 1 HOUR),
(1, 1, 'Noted, Ma''am. I will review it today.', NOW() - INTERVAL 45 MINUTE),
(1, 2, 'Make sure to include your LMS comparison and prototype improvement.', NOW() - INTERVAL 30 MINUTE);
