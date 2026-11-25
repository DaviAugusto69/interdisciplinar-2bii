-- Apply once to the existing database exported as meu_mvc.
-- Back up first. MySQL DDL commits each statement independently.
ALTER TABLE users
    MODIFY birth_date DATE NULL,
    ADD COLUMN address VARCHAR(250) NULL,
    ADD COLUMN phone VARCHAR(32) NULL,
    ADD COLUMN password_hash VARCHAR(255) NULL,
    ADD COLUMN font_size VARCHAR(10) NULL,
    ADD COLUMN color_theme VARCHAR(10) NULL,
    ADD COLUMN archived_at DATETIME NULL;

CREATE TABLE IF NOT EXISTS support_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(50) NOT NULL,
    message TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE lessons
    ADD COLUMN is_published TINYINT(1) NOT NULL DEFAULT 1,
    ADD COLUMN published_at DATETIME NULL,
    ADD COLUMN archived_at DATETIME NULL,
    ADD COLUMN category_id INT NULL,
    ADD CONSTRAINT fk_lessons_category FOREIGN KEY (category_id) REFERENCES categories(id);

UPDATE lessons SET published_at = created_at WHERE is_published = 1 AND published_at IS NULL;

ALTER TABLE user_progress MODIFY lesson_slug VARCHAR(120) NOT NULL;

CREATE TABLE activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lesson_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    prompt TEXT NOT NULL,
    choices TEXT NULL,
    correct_answer VARCHAR(150) NULL,
    points INT UNSIGNED NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT fk_activities_lesson FOREIGN KEY (lesson_id) REFERENCES lessons(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE activity_results (
    user_id INT NOT NULL,
    activity_id INT NOT NULL,
    attempts INT UNSIGNED NOT NULL DEFAULT 1,
    points_awarded INT UNSIGNED NULL,
    completed_at DATETIME NULL,
    PRIMARY KEY (user_id, activity_id),
    CONSTRAINT fk_activity_results_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_activity_results_activity FOREIGN KEY (activity_id) REFERENCES activities(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE rewards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    required_points INT UNSIGNED NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE user_rewards (
    user_id INT NOT NULL,
    reward_id INT NOT NULL,
    awarded_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, reward_id),
    CONSTRAINT fk_user_rewards_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_user_rewards_reward FOREIGN KEY (reward_id) REFERENCES rewards(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
