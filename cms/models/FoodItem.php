<?php
class FoodItem
{
    public static function allWithCategory(?int $categoryId = null): array
    {
        $sql = 'SELECT i.*, c.name AS category_name FROM food_items i JOIN food_categories c ON i.category_id = c.id';
        $values = [];
        if ($categoryId) {
            $sql .= ' WHERE i.category_id = ?';
            $values[] = $categoryId;
        }
        $sql .= ' ORDER BY i.is_pinned DESC, i.is_recommended DESC, i.sort_order ASC, i.id DESC';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute($values);
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM food_items WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::pdo()->prepare('INSERT INTO food_items (category_id, title, subtitle, cover_url, content, address, phone, business_hours, recommend_score, latitude, longitude, is_recommended, is_pinned, sort_order, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
        $stmt->execute([
            $data['category_id'],
            $data['title'],
            $data['subtitle'] ?? '',
            $data['cover_url'] ?? '',
            $data['content'] ?? '',
            $data['address'] ?? '',
            $data['phone'] ?? '',
            $data['business_hours'] ?? '',
            $data['recommend_score'] ?? 0,
            $data['latitude'] !== '' ? $data['latitude'] : null,
            $data['longitude'] !== '' ? $data['longitude'] : null,
            !empty($data['is_recommended']) ? 1 : 0,
            !empty($data['is_pinned']) ? 1 : 0,
            $data['sort_order'] ?? 0,
            !empty($data['is_active']) ? 1 : 0,
        ]);
        return (int)Database::pdo()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $fields = [];
        $values = [];

        $map = [
            'category_id' => 'category_id',
            'title' => 'title',
            'subtitle' => 'subtitle',
            'cover_url' => 'cover_url',
            'content' => 'content',
            'address' => 'address',
            'phone' => 'phone',
            'business_hours' => 'business_hours',
            'recommend_score' => 'recommend_score',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'is_recommended' => 'is_recommended',
            'is_pinned' => 'is_pinned',
            'sort_order' => 'sort_order',
            'is_active' => 'is_active',
        ];

        foreach ($map as $key => $field) {
            if (array_key_exists($key, $data)) {
                $fields[] = $field . ' = ?';
                $values[] = $data[$key];
            }
        }

        $fields[] = 'updated_at = NOW()';
        $values[] = $id;

        $sql = 'UPDATE food_items SET ' . implode(', ', $fields) . ' WHERE id = ?';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute($values);
    }

    public static function delete(int $id): void
    {
        $stmt = Database::pdo()->prepare('DELETE FROM food_items WHERE id = ?');
        $stmt->execute([$id]);
    }

    public static function listForCategory(int $categoryId, int $limit = 20, int $offset = 0): array
    {
        $stmt = Database::pdo()->prepare('SELECT id, category_id, title, subtitle, cover_url, content, address, phone, business_hours, recommend_score, latitude, longitude, is_recommended, is_pinned, sort_order, is_active, created_at, updated_at FROM food_items WHERE category_id = ? AND is_active = 1 ORDER BY is_pinned DESC, is_recommended DESC, sort_order ASC, id DESC LIMIT ? OFFSET ?');
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function listCoverByCategory(int $categoryId, int $limit = 6): array
    {
        $items = self::listRecommendedByCategory($categoryId, $limit);
        $remaining = $limit - count($items);

        if ($remaining <= 0) {
            return $items;
        }

        $excludeIds = array_column($items, 'id');
        $sql = 'SELECT id, category_id, title, subtitle, cover_url, content, address, phone, business_hours, recommend_score, latitude, longitude, is_recommended, is_pinned, sort_order, is_active, created_at, updated_at FROM food_items WHERE category_id = ? AND is_active = 1';
        if (!empty($excludeIds)) {
            $sql .= ' AND id NOT IN (' . implode(', ', array_fill(0, count($excludeIds), '?')) . ')';
        }
        $sql .= ' ORDER BY is_pinned DESC, is_recommended DESC, sort_order ASC, id DESC LIMIT ?';

        $stmt = Database::pdo()->prepare($sql);
        $bindIndex = 1;
        $stmt->bindValue($bindIndex++, $categoryId, PDO::PARAM_INT);
        foreach ($excludeIds as $excludeId) {
            $stmt->bindValue($bindIndex++, (int)$excludeId, PDO::PARAM_INT);
        }
        $stmt->bindValue($bindIndex++, $remaining, PDO::PARAM_INT);
        $stmt->execute();
        $fallback = $stmt->fetchAll();

        return array_merge($items, $fallback);
    }

    public static function listRecommendedByCategory(int $categoryId, int $limit = 6): array
    {
        $stmt = Database::pdo()->prepare('SELECT id, category_id, title, subtitle, cover_url, content, address, phone, business_hours, recommend_score, latitude, longitude, is_recommended, is_pinned, sort_order, is_active, created_at, updated_at FROM food_items WHERE category_id = ? AND is_active = 1 AND is_recommended = 1 ORDER BY is_pinned DESC, sort_order ASC, id DESC LIMIT ?');
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
