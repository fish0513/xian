<?php
class TravelItem
{
    public static function countWithCategory(?int $categoryId = null): int
    {
        $sql = 'SELECT COUNT(*) AS cnt FROM travel_items i';
        $values = [];
        if ($categoryId) {
            $sql .= ' WHERE i.category_id = ?';
            $values[] = $categoryId;
        }
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute($values);
        $row = $stmt->fetch();
        return (int)($row['cnt'] ?? 0);
    }

    public static function allWithCategory(?int $categoryId = null, int $limit = 20, int $offset = 0): array
    {
        $sql = 'SELECT i.id, i.category_id, i.title, i.subtitle, i.cover_url, i.is_recommended, i.is_pinned, i.sort_order, i.is_active, i.created_at, i.updated_at, c.name AS category_name FROM travel_items i JOIN travel_categories c ON i.category_id = c.id';
        $values = [];
        if ($categoryId) {
            $sql .= ' WHERE i.category_id = ?';
            $values[] = $categoryId;
        }
        $sql .= ' ORDER BY i.is_pinned DESC, i.is_recommended DESC, i.sort_order ASC, i.id DESC LIMIT ? OFFSET ?';
        $stmt = Database::pdo()->prepare($sql);

        $bindIndex = 1;
        foreach ($values as $v) {
            $stmt->bindValue($bindIndex++, $v, PDO::PARAM_INT);
        }
        $stmt->bindValue($bindIndex++, max(1, $limit), PDO::PARAM_INT);
        $stmt->bindValue($bindIndex++, max(0, $offset), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM travel_items WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::pdo()->prepare('INSERT INTO travel_items (category_id, title, subtitle, cover_url, content, address, phone, business_hours, ticket_price, latitude, longitude, is_recommended, is_pinned, sort_order, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
        $stmt->execute([
            $data['category_id'],
            $data['title'],
            $data['subtitle'] ?? '',
            $data['cover_url'] ?? '',
            $data['content'] ?? '',
            $data['address'] ?? '',
            $data['phone'] ?? '',
            $data['business_hours'] ?? '',
            $data['ticket_price'] !== '' ? $data['ticket_price'] : null,
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
            'ticket_price' => 'ticket_price',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'is_recommended' => 'is_recommended',
            'is_pinned' => 'is_pinned',
            'sort_order' => 'sort_order',
            'is_active' => 'is_active',
        ];

        foreach ($map as $key => $field) {
            if (array_key_exists($key, $data)) {
                if ($key === 'ticket_price' || $key === 'latitude' || $key === 'longitude') {
                    $fields[] = $field . ' = ?';
                    $values[] = $data[$key] !== '' ? $data[$key] : null;
                } else {
                    $fields[] = $field . ' = ?';
                    $values[] = $data[$key];
                }
            }
        }

        $fields[] = 'updated_at = NOW()';
        $values[] = $id;

        $sql = 'UPDATE travel_items SET ' . implode(', ', $fields) . ' WHERE id = ?';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute($values);
    }

    public static function delete(int $id): void
    {
        $stmt = Database::pdo()->prepare('DELETE FROM travel_items WHERE id = ?');
        $stmt->execute([$id]);
    }

    public static function listForCategory(int $categoryId, int $limit = 20, int $offset = 0): array
    {
        $stmt = Database::pdo()->prepare('SELECT id, category_id, title, subtitle, cover_url, content, address, phone, business_hours, ticket_price, latitude, longitude, is_recommended, is_pinned, sort_order, is_active, created_at, updated_at FROM travel_items WHERE category_id = ? AND is_active = 1 ORDER BY is_pinned DESC, is_recommended DESC, sort_order ASC, id DESC LIMIT ? OFFSET ?');
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function listCoverByCategory(int $categoryId, int $limit = 3): array
    {
        $items = self::listRecommendedByCategory($categoryId, $limit);
        $remaining = $limit - count($items);

        if ($remaining <= 0) {
            return $items;
        }

        $excludeIds = array_column($items, 'id');
        $sql = 'SELECT id, category_id, title, subtitle, cover_url, content, address, phone, business_hours, ticket_price, latitude, longitude, is_recommended, is_pinned, sort_order, is_active, created_at, updated_at FROM travel_items WHERE category_id = ? AND is_active = 1';
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

    public static function listRecommendedByCategory(int $categoryId, int $limit = 3): array
    {
        $stmt = Database::pdo()->prepare('SELECT id, category_id, title, subtitle, cover_url, content, address, phone, business_hours, ticket_price, latitude, longitude, is_recommended, is_pinned, sort_order, is_active, created_at, updated_at FROM travel_items WHERE category_id = ? AND is_active = 1 AND is_recommended = 1 ORDER BY is_pinned DESC, sort_order ASC, id DESC LIMIT ?');
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
