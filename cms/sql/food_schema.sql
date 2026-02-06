CREATE TABLE IF NOT EXISTS food_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS food_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    subtitle VARCHAR(200) DEFAULT '',
    cover_url VARCHAR(500) DEFAULT '',
    content MEDIUMTEXT,
    address VARCHAR(255) DEFAULT '',
    phone VARCHAR(50) DEFAULT '',
    business_hours VARCHAR(100) DEFAULT '',
    recommend_score INT NOT NULL DEFAULT 0,
    latitude DECIMAL(10,6) DEFAULT NULL,
    longitude DECIMAL(10,6) DEFAULT NULL,
    is_recommended TINYINT(1) NOT NULL DEFAULT 0,
    is_pinned TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    CONSTRAINT fk_food_items_category_id FOREIGN KEY (category_id) REFERENCES food_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_food_items_category_id ON food_items(category_id);
CREATE INDEX idx_food_items_recommend ON food_items(is_recommended, is_pinned, sort_order);
CREATE INDEX idx_food_items_active ON food_items(is_active);

INSERT INTO food_categories (code, name, sort_order, created_at, updated_at)
VALUES
    ('food_street', '美食街', 1, NOW(), NOW()),
    ('country_food', '乡村美食', 2, NOW(), NOW()),
    ('special_food', '特色美食', 3, NOW(), NOW())
ON DUPLICATE KEY UPDATE name = VALUES(name), sort_order = VALUES(sort_order), updated_at = NOW();
