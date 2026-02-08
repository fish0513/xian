<?php
class TravelCategoryController
{
    public function index(): void
    {
        Auth::requireLogin();
        TravelCategory::ensureDefaults();
        $categories = TravelCategory::all();
        View::render('travel/categories/index', ['categories' => $categories]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        View::render('travel/categories/form', ['category' => null, 'error' => null, 'mode' => 'create']);
    }

    public function store(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            View::render('travel/categories/form', ['category' => null, 'error' => '安全校验失败', 'mode' => 'create']);
            return;
        }

        $code = trim($_POST['code'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);

        if ($code === '' || $name === '') {
            View::render('travel/categories/form', ['category' => $_POST, 'error' => '编码和名称不能为空', 'mode' => 'create']);
            return;
        }

        $exists = TravelCategory::findByCode($code);
        if ($exists) {
            View::render('travel/categories/form', ['category' => $_POST, 'error' => '编码已存在', 'mode' => 'create']);
            return;
        }

        TravelCategory::create([
            'code' => $code,
            'name' => $name,
            'sort_order' => $sortOrder,
        ]);

        Auth::redirect('/admin/travel/categories');
    }

    public function edit(): void
    {
        Auth::requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $category = $id ? TravelCategory::findById($id) : null;
        if (!$category) {
            Auth::redirect('/admin/travel/categories');
        }
        View::render('travel/categories/form', ['category' => $category, 'error' => null, 'mode' => 'edit']);
    }

    public function update(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            View::render('travel/categories/form', ['category' => $_POST, 'error' => '安全校验失败', 'mode' => 'edit']);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $category = $id ? TravelCategory::findById($id) : null;
        if (!$category) {
            Auth::redirect('/admin/travel/categories');
        }

        $code = trim($_POST['code'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);

        if ($code === '' || $name === '') {
            View::render('travel/categories/form', ['category' => $_POST, 'error' => '编码和名称不能为空', 'mode' => 'edit']);
            return;
        }

        $exists = TravelCategory::findByCode($code);
        if ($exists && (int)$exists['id'] !== $id) {
            View::render('travel/categories/form', ['category' => $_POST, 'error' => '编码已存在', 'mode' => 'edit']);
            return;
        }

        TravelCategory::update($id, [
            'code' => $code,
            'name' => $name,
            'sort_order' => $sortOrder,
        ]);

        Auth::redirect('/admin/travel/categories');
    }

    public function destroy(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            Auth::redirect('/admin/travel/categories');
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            TravelCategory::delete($id);
        }
        Auth::redirect('/admin/travel/categories');
    }
}
