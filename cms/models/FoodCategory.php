<?php
class FoodCategory
{
    public static function all(): array
    {
        $stmt = Database::pdo()->query('SELECT id, code, name, sort_order, created_at, updated_at FROM food_categories ORDER BY sort_order ASC, id ASC');
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT id, code, name, sort_order FROM food_categories WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findByCode(string $code): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT id, code, name, sort_order FROM food_categories WHERE code = ?');
        $stmt->execute([$code]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::pdo()->prepare('INSERT INTO food_categories (code, name, sort_order, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())');
        $stmt->execute([
            $data['code'],
            $data['name'],
            $data['sort_order'] ?? 0,
        ]);
        return (int)Database::pdo()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $fields = [];
        $values = [];

        if (isset($data['code'])) {
            $fields[] = 'code = ?';
            $values[] = $data['code'];
        }
        if (isset($data['name'])) {
            $fields[] = 'name = ?';
            $values[] = $data['name'];
        }
        if (isset($data['sort_order'])) {
            $fields[] = 'sort_order = ?';
            $values[] = (int)$data['sort_order'];
        }

        $fields[] = 'updated_at = NOW()';
        $values[] = $id;

        $sql = 'UPDATE food_categories SET ' . implode(', ', $fields) . ' WHERE id = ?';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute($values);
    }

    public static function delete(int $id): void
    {
        $stmt = Database::pdo()->prepare('DELETE FROM food_categories WHERE id = ?');
        $stmt->execute([$id]);
    }

    public static function ensureDefaults(): void
    {
        $defaults = [
            ['code' => 'food_street', 'name' => '美食街', 'sort_order' => 1],
            ['code' => 'country_food', 'name' => '乡村美食', 'sort_order' => 2],
            ['code' => 'special_food', 'name' => '特色美食', 'sort_order' => 3],
        ];

        $stmt = Database::pdo()->query('SELECT code FROM food_categories');
        $existing = $stmt->fetchAll();
        $codes = array_column($existing, 'code');

        foreach ($defaults as $item) {
            if (!in_array($item['code'], $codes, true)) {
                self::create($item);
            }
        }
    }
}
