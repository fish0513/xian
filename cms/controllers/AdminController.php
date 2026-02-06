<?php
class AdminController
{
    public function index(): void
    {
        Auth::requireLogin();
        $admins = Admin::all();
        View::render('admin/index', ['admins' => $admins]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        View::render('admin/form', ['admin' => null, 'error' => null, 'mode' => 'create']);
    }

    public function store(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            View::render('admin/form', ['admin' => null, 'error' => '安全校验失败', 'mode' => 'create']);
            return;
        }

        $username = trim($_POST['username'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            View::render('admin/form', ['admin' => $_POST, 'error' => '用户名和密码不能为空', 'mode' => 'create']);
            return;
        }

        $exists = Admin::findByUsername($username);
        if ($exists) {
            View::render('admin/form', ['admin' => $_POST, 'error' => '用户名已存在', 'mode' => 'create']);
            return;
        }

        Admin::create([
            'username' => $username,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'name' => $name,
            'email' => $email,
        ]);

        Auth::redirect('/admin/admins');
    }

    public function edit(): void
    {
        Auth::requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $admin = $id ? Admin::findById($id) : null;
        if (!$admin) {
            Auth::redirect('/admin/admins');
        }
        View::render('admin/form', ['admin' => $admin, 'error' => null, 'mode' => 'edit']);
    }

    public function update(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            View::render('admin/form', ['admin' => $_POST, 'error' => '安全校验失败', 'mode' => 'edit']);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $admin = $id ? Admin::findById($id) : null;
        if (!$admin) {
            Auth::redirect('/admin/admins');
        }

        $username = trim($_POST['username'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $data = [
            'username' => $username,
            'name' => $name,
            'email' => $email,
        ];
        if ($password !== '') {
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        Admin::update($id, $data);
        Auth::redirect('/admin/admins');
    }

    public function destroy(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            Auth::redirect('/admin/admins');
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Admin::delete($id);
        }
        Auth::redirect('/admin/admins');
    }
}
