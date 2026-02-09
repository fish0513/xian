<?php
class AdminAuthController
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            Auth::redirect(Auth::isSuperAdmin() ? '/admin/admins' : '/admin/food/items');
        }
        View::render('auth/login', ['error' => null, 'username' => '']);
    }

    public function login(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $csrf = $_POST['csrf_token'] ?? '';

        if (!Auth::verifyCsrf($csrf)) {
            View::render('auth/login', ['error' => '安全校验失败', 'username' => $username]);
            return;
        }

        $admin = Admin::findByUsername($username);
        if (!$admin || !password_verify($password, $admin['password_hash'])) {
            View::render('auth/login', ['error' => '账号或密码错误', 'username' => $username]);
            return;
        }

        $role = isset($admin['role']) ? (string)$admin['role'] : 'super';
        Auth::login((int)$admin['id'], $role, (string)$admin['username']);
        Auth::redirect(Auth::isSuperAdmin() ? '/admin/admins' : '/admin/food/items');
    }

    public function logout(): void
    {
        $csrf = $_POST['csrf_token'] ?? '';
        if (Auth::verifyCsrf($csrf)) {
            Auth::logout();
        }
        Auth::redirect('/admin/login');
    }
}
