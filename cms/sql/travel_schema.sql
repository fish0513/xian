CREATE TABLE IF NOT EXISTS travel_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS travel_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    subtitle VARCHAR(200) DEFAULT '',
    cover_url VARCHAR(500) DEFAULT '',
    content MEDIUMTEXT,
    address VARCHAR(255) DEFAULT '',
    phone VARCHAR(50) DEFAULT '',
    business_hours VARCHAR(100) DEFAULT '',
    ticket_price DECIMAL(10,2) DEFAULT NULL,
    latitude DECIMAL(10,6) DEFAULT NULL,
    longitude DECIMAL(11,6) DEFAULT NULL,
    is_recommended TINYINT(1) NOT NULL DEFAULT 0,
    is_pinned TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    CONSTRAINT fk_travel_items_category_id FOREIGN KEY (category_id) REFERENCES travel_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_travel_items_category_id ON travel_items(category_id);
CREATE INDEX idx_travel_items_recommend ON travel_items(is_recommended, is_pinned, sort_order);
CREATE INDEX idx_travel_items_active ON travel_items(is_active);

INSERT INTO travel_categories (code, name, sort_order, created_at, updated_at)
VALUES
    ('culture_scenic', '文旅景点', 1, NOW(), NOW()),
    ('town_scenic', '乡镇景点', 2, NOW(), NOW())
ON DUPLICATE KEY UPDATE name = VALUES(name), sort_order = VALUES(sort_order), updated_at = NOW();
