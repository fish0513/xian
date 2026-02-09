<?php
class Admin
{
    private static ?bool $hasRoleColumn = null;

    private static function hasRoleColumn(): bool
    {
        if (self::$hasRoleColumn !== null) {
            return self::$hasRoleColumn;
        }
        try {
            $stmt = Database::pdo()->query("SHOW COLUMNS FROM admins LIKE 'role'");
            $row = $stmt->fetch();
            self::$hasRoleColumn = !empty($row);
        } catch (Throwable $e) {
            self::$hasRoleColumn = false;
        }
        return self::$hasRoleColumn;
    }

    public static function all(): array
    {
        $sql = self::hasRoleColumn()
            ? 'SELECT id, username, name, email, role, created_at, updated_at FROM admins ORDER BY id DESC'
            : "SELECT id, username, name, email, 'super' AS role, created_at, updated_at FROM admins ORDER BY id DESC";
        $stmt = Database::pdo()->query($sql);
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $sql = self::hasRoleColumn()
            ? 'SELECT id, username, name, email, role, password_hash FROM admins WHERE id = ?'
            : "SELECT id, username, name, email, 'super' AS role, password_hash FROM admins WHERE id = ?";
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findByUsername(string $username): ?array
    {
        $sql = self::hasRoleColumn()
            ? 'SELECT id, username, name, email, role, password_hash FROM admins WHERE username = ?'
            : "SELECT id, username, name, email, 'super' AS role, password_hash FROM admins WHERE username = ?";
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        if (self::hasRoleColumn()) {
            $stmt = Database::pdo()->prepare('INSERT INTO admins (username, password_hash, name, email, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())');
            $stmt->execute([
                $data['username'],
                $data['password_hash'],
                $data['name'],
                $data['email'],
                $data['role'] ?? 'normal',
            ]);
        } else {
            $stmt = Database::pdo()->prepare('INSERT INTO admins (username, password_hash, name, email, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())');
            $stmt->execute([
                $data['username'],
                $data['password_hash'],
                $data['name'],
                $data['email'],
            ]);
        }
        return (int)Database::pdo()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $fields = [];
        $values = [];

        if (isset($data['username'])) {
            $fields[] = 'username = ?';
            $values[] = $data['username'];
        }
        if (isset($data['name'])) {
            $fields[] = 'name = ?';
            $values[] = $data['name'];
        }
        if (isset($data['email'])) {
            $fields[] = 'email = ?';
            $values[] = $data['email'];
        }
        if (isset($data['role']) && self::hasRoleColumn()) {
            $fields[] = 'role = ?';
            $values[] = $data['role'];
        }
        if (!empty($data['password_hash'])) {
            $fields[] = 'password_hash = ?';
            $values[] = $data['password_hash'];
        }

        $fields[] = 'updated_at = NOW()';
        $values[] = $id;

        $sql = 'UPDATE admins SET ' . implode(', ', $fields) . ' WHERE id = ?';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute($values);
    }

    public static function delete(int $id): void
    {
        $stmt = Database::pdo()->prepare('DELETE FROM admins WHERE id = ?');
        $stmt->execute([$id]);
    }
}
