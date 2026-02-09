<?php
class LiveItem
{
    public static function countWithCategory(?int $categoryId = null): int
    {
        $sql = 'SELECT COUNT(*) AS cnt FROM live_items i';
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
        $sql = 'SELECT i.id, i.category_id, i.name, i.cover_url, i.address, i.phone, i.is_recommended, i.is_pinned, i.sort_order, i.is_active, i.created_at, i.updated_at, c.name AS category_name FROM live_items i JOIN live_categories c ON i.category_id = c.id';
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
        $stmt = Database::pdo()->prepare('SELECT * FROM live_items WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::pdo()->prepare('INSERT INTO live_items (category_id, name, cover_url, intro, address, phone, is_recommended, is_pinned, sort_order, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
        $stmt->execute([
            $data['category_id'],
            $data['name'],
            $data['cover_url'] ?? '',
            $data['intro'] ?? '',
            $data['address'] ?? '',
            $data['phone'] ?? '',
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
            'name' => 'name',
            'cover_url' => 'cover_url',
            'intro' => 'intro',
            'address' => 'address',
            'phone' => 'phone',
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

        $sql = 'UPDATE live_items SET ' . implode(', ', $fields) . ' WHERE id = ?';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute($values);
    }

    public static function delete(int $id): void
    {
        $stmt = Database::pdo()->prepare('DELETE FROM live_items WHERE id = ?');
        $stmt->execute([$id]);
    }

    public static function listForCategory(int $categoryId, int $limit = 20, int $offset = 0): array
    {
        $stmt = Database::pdo()->prepare('SELECT id, category_id, name, cover_url, intro, address, phone, is_recommended, is_pinned, sort_order, is_active, created_at, updated_at FROM live_items WHERE category_id = ? AND is_active = 1 ORDER BY is_pinned DESC, is_recommended DESC, sort_order ASC, id DESC LIMIT ? OFFSET ?');
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
        $sql = 'SELECT id, category_id, name, cover_url, intro, address, phone, is_recommended, is_pinned, sort_order, is_active, created_at, updated_at FROM live_items WHERE category_id = ? AND is_active = 1';
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
        $stmt = Database::pdo()->prepare('SELECT id, category_id, name, cover_url, intro, address, phone, is_recommended, is_pinned, sort_order, is_active, created_at, updated_at FROM live_items WHERE category_id = ? AND is_active = 1 AND is_recommended = 1 ORDER BY is_pinned DESC, sort_order ASC, id DESC LIMIT ?');
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
