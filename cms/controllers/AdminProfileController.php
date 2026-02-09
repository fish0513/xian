<?php
class AdminProfileController
{
    public function edit(): void
    {
        Auth::requireLogin();
        $id = (int)Auth::id();
        $admin = $id ? Admin::findById($id) : null;
        if (!$admin) {
            Auth::logout();
            Auth::redirect('/admin/login');
        }
        View::render('admin/profile', ['admin' => $admin, 'error' => null, 'success' => null]);
    }

    public function update(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            $admin = Admin::findById((int)Auth::id());
            View::render('admin/profile', ['admin' => $admin ?: [], 'error' => '安全校验失败', 'success' => null]);
            return;
        }

        $id = (int)Auth::id();
        $admin = $id ? Admin::findById($id) : null;
        if (!$admin) {
            Auth::logout();
            Auth::redirect('/admin/login');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $newPassword2 = $_POST['new_password2'] ?? '';

        $data = [
            'name' => $name,
            'email' => $email,
        ];

        if ($newPassword !== '' || $newPassword2 !== '' || $currentPassword !== '') {
            if ($newPassword === '' || $newPassword2 === '' || $currentPassword === '') {
                View::render('admin/profile', ['admin' => array_merge($admin, $data), 'error' => '修改密码需要填写原密码与两次新密码', 'success' => null]);
                return;
            }
            if (!password_verify($currentPassword, $admin['password_hash'])) {
                View::render('admin/profile', ['admin' => array_merge($admin, $data), 'error' => '原密码不正确', 'success' => null]);
                return;
            }
            if ($newPassword !== $newPassword2) {
                View::render('admin/profile', ['admin' => array_merge($admin, $data), 'error' => '两次新密码不一致', 'success' => null]);
                return;
            }
            $data['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        Admin::update($id, $data);
        $admin = Admin::findById($id);
        View::render('admin/profile', ['admin' => $admin ?: [], 'error' => null, 'success' => '保存成功']);
    }
}
