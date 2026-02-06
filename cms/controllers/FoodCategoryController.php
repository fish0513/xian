<?php
class FoodCategoryController
{
    public function index(): void
    {
        Auth::requireLogin();
        FoodCategory::ensureDefaults();
        $categories = FoodCategory::all();
        View::render('food/categories/index', ['categories' => $categories]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        View::render('food/categories/form', ['category' => null, 'error' => null, 'mode' => 'create']);
    }

    public function store(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            View::render('food/categories/form', ['category' => null, 'error' => '安全校验失败', 'mode' => 'create']);
            return;
        }

        $code = trim($_POST['code'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);

        if ($code === '' || $name === '') {
            View::render('food/categories/form', ['category' => $_POST, 'error' => '编码和名称不能为空', 'mode' => 'create']);
            return;
        }

        $exists = FoodCategory::findByCode($code);
        if ($exists) {
            View::render('food/categories/form', ['category' => $_POST, 'error' => '编码已存在', 'mode' => 'create']);
            return;
        }

        FoodCategory::create([
            'code' => $code,
            'name' => $name,
            'sort_order' => $sortOrder,
        ]);

        Auth::redirect('/admin/food/categories');
    }

    public function edit(): void
    {
        Auth::requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $category = $id ? FoodCategory::findById($id) : null;
        if (!$category) {
            Auth::redirect('/admin/food/categories');
        }
        View::render('food/categories/form', ['category' => $category, 'error' => null, 'mode' => 'edit']);
    }

    public function update(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            View::render('food/categories/form', ['category' => $_POST, 'error' => '安全校验失败', 'mode' => 'edit']);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $category = $id ? FoodCategory::findById($id) : null;
        if (!$category) {
            Auth::redirect('/admin/food/categories');
        }

        $code = trim($_POST['code'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);

        if ($code === '' || $name === '') {
            View::render('food/categories/form', ['category' => $_POST, 'error' => '编码和名称不能为空', 'mode' => 'edit']);
            return;
        }

        $exists = FoodCategory::findByCode($code);
        if ($exists && (int)$exists['id'] !== $id) {
            View::render('food/categories/form', ['category' => $_POST, 'error' => '编码已存在', 'mode' => 'edit']);
            return;
        }

        FoodCategory::update($id, [
            'code' => $code,
            'name' => $name,
            'sort_order' => $sortOrder,
        ]);

        Auth::redirect('/admin/food/categories');
    }

    public function destroy(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            Auth::redirect('/admin/food/categories');
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            FoodCategory::delete($id);
        }
        Auth::redirect('/admin/food/categories');
    }
}
