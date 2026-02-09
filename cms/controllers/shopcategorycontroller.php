<?php
class ShopCategoryController
{
    public function index(): void
    {
        Auth::requireLogin();
        ShopCategory::ensureDefaults();
        $categories = ShopCategory::all();
        View::render('shop/categories/index', ['categories' => $categories]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        View::render('shop/categories/form', ['category' => null, 'error' => null, 'mode' => 'create']);
    }

    public function store(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            View::render('shop/categories/form', ['category' => null, 'error' => '安全校验失败', 'mode' => 'create']);
            return;
        }

        $code = trim($_POST['code'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);

        if ($code === '' || $name === '') {
            View::render('shop/categories/form', ['category' => $_POST, 'error' => '编码和名称不能为空', 'mode' => 'create']);
            return;
        }

        $exists = ShopCategory::findByCode($code);
        if ($exists) {
            View::render('shop/categories/form', ['category' => $_POST, 'error' => '编码已存在', 'mode' => 'create']);
            return;
        }

        ShopCategory::create([
            'code' => $code,
            'name' => $name,
            'sort_order' => $sortOrder,
        ]);

        Auth::redirect('/admin/shop/categories');
    }

    public function edit(): void
    {
        Auth::requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $category = $id ? ShopCategory::findById($id) : null;
        if (!$category) {
            Auth::redirect('/admin/shop/categories');
        }
        View::render('shop/categories/form', ['category' => $category, 'error' => null, 'mode' => 'edit']);
    }

    public function update(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            View::render('shop/categories/form', ['category' => $_POST, 'error' => '安全校验失败', 'mode' => 'edit']);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $category = $id ? ShopCategory::findById($id) : null;
        if (!$category) {
            Auth::redirect('/admin/shop/categories');
        }

        $code = trim($_POST['code'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);

        if ($code === '' || $name === '') {
            View::render('shop/categories/form', ['category' => $_POST, 'error' => '编码和名称不能为空', 'mode' => 'edit']);
            return;
        }

        $exists = ShopCategory::findByCode($code);
        if ($exists && (int)$exists['id'] !== $id) {
            View::render('shop/categories/form', ['category' => $_POST, 'error' => '编码已存在', 'mode' => 'edit']);
            return;
        }

        ShopCategory::update($id, [
            'code' => $code,
            'name' => $name,
            'sort_order' => $sortOrder,
        ]);

        Auth::redirect('/admin/shop/categories');
    }

    public function destroy(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            Auth::redirect('/admin/shop/categories');
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            ShopCategory::delete($id);
        }
        Auth::redirect('/admin/shop/categories');
    }
}
