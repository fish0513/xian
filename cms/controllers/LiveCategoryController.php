<?php
class LiveCategoryController
{
    public function index(): void
    {
        Auth::requireLogin();
        LiveCategory::ensureDefaults();
        $categories = LiveCategory::all();
        View::render('live/categories/index', ['categories' => $categories]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        View::render('live/categories/form', ['category' => null, 'error' => null, 'mode' => 'create']);
    }

    public function store(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            View::render('live/categories/form', ['category' => null, 'error' => '安全校验失败', 'mode' => 'create']);
            return;
        }

        $code = trim($_POST['code'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);

        if ($code === '' || $name === '') {
            View::render('live/categories/form', ['category' => $_POST, 'error' => '编码和名称不能为空', 'mode' => 'create']);
            return;
        }

        $exists = LiveCategory::findByCode($code);
        if ($exists) {
            View::render('live/categories/form', ['category' => $_POST, 'error' => '编码已存在', 'mode' => 'create']);
            return;
        }

        LiveCategory::create([
            'code' => $code,
            'name' => $name,
            'sort_order' => $sortOrder,
        ]);

        Auth::redirect('/admin/live/categories');
    }

    public function edit(): void
    {
        Auth::requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $category = $id ? LiveCategory::findById($id) : null;
        if (!$category) {
            Auth::redirect('/admin/live/categories');
        }
        View::render('live/categories/form', ['category' => $category, 'error' => null, 'mode' => 'edit']);
    }

    public function update(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            View::render('live/categories/form', ['category' => $_POST, 'error' => '安全校验失败', 'mode' => 'edit']);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $category = $id ? LiveCategory::findById($id) : null;
        if (!$category) {
            Auth::redirect('/admin/live/categories');
        }

        $code = trim($_POST['code'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);

        if ($code === '' || $name === '') {
            View::render('live/categories/form', ['category' => $_POST, 'error' => '编码和名称不能为空', 'mode' => 'edit']);
            return;
        }

        $exists = LiveCategory::findByCode($code);
        if ($exists && (int)$exists['id'] !== $id) {
            View::render('live/categories/form', ['category' => $_POST, 'error' => '编码已存在', 'mode' => 'edit']);
            return;
        }

        LiveCategory::update($id, [
            'code' => $code,
            'name' => $name,
            'sort_order' => $sortOrder,
        ]);

        Auth::redirect('/admin/live/categories');
    }

    public function destroy(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            Auth::redirect('/admin/live/categories');
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            LiveCategory::delete($id);
        }
        Auth::redirect('/admin/live/categories');
    }
}
