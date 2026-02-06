<?php
class AdminAuthController
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            Auth::redirect('/admin/admins');
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

        Auth::login((int)$admin['id']);
        Auth::redirect('/admin/admins');
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
