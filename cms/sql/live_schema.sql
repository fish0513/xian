CREATE TABLE IF NOT EXISTS live_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS live_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    cover_url VARCHAR(500) DEFAULT '',
    intro MEDIUMTEXT,
    address VARCHAR(255) DEFAULT '',
    phone VARCHAR(50) DEFAULT '',
    is_recommended TINYINT(1) NOT NULL DEFAULT 0,
    is_pinned TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    CONSTRAINT fk_live_items_category_id FOREIGN KEY (category_id) REFERENCES live_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_live_items_category_id ON live_items(category_id);
CREATE INDEX idx_live_items_recommend ON live_items(is_recommended, is_pinned, sort_order);
CREATE INDEX idx_live_items_active ON live_items(is_active);

INSERT INTO live_categories (code, name, sort_order, created_at, updated_at)
VALUES
    ('hotel', '酒店', 1, NOW(), NOW()),
    ('homestay', '名宿', 2, NOW(), NOW())
ON DUPLICATE KEY UPDATE name = VALUES(name), sort_order = VALUES(sort_order), updated_at = NOW();
