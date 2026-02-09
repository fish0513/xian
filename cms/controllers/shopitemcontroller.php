<?php
class ShopItemController
{
    public function index(): void
    {
        Auth::requireLogin();
        ShopCategory::ensureDefaults();
        $categories = ShopCategory::all();
        $categoryId = (int)($_GET['category_id'] ?? 0);
        $pageSize = 20;
        $page = (int)($_GET['page'] ?? 1);
        if ($page < 1) {
            $page = 1;
        }
        $filterCategoryId = $categoryId > 0 ? $categoryId : null;
        $total = ShopItem::countWithCategory($filterCategoryId);
        $totalPages = (int)max(1, (int)ceil($total / $pageSize));
        if ($page > $totalPages) {
            $page = $totalPages;
        }
        $offset = ($page - 1) * $pageSize;
        $items = ShopItem::allWithCategory($filterCategoryId, $pageSize, $offset);
        View::render('shop/items/index', [
            'items' => $items,
            'categories' => $categories,
            'categoryId' => $categoryId,
            'page' => $page,
            'pageSize' => $pageSize,
            'total' => $total,
            'totalPages' => $totalPages,
        ]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        ShopCategory::ensureDefaults();
        $categories = ShopCategory::all();
        View::render('shop/items/form', ['item' => null, 'categories' => $categories, 'error' => null, 'mode' => 'create']);
    }

    public function store(): void
    {
        Auth::requireLogin();
        ShopCategory::ensureDefaults();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            View::render('shop/items/form', ['item' => null, 'categories' => ShopCategory::all(), 'error' => '安全校验失败', 'mode' => 'create']);
            return;
        }

        $categoryId = (int)($_POST['category_id'] ?? 0);
        $shopName = trim($_POST['shop_name'] ?? '');
        $shopLogo = trim($_POST['shop_logo'] ?? '');
        $shopImages = trim($_POST['shop_images'] ?? '');
        $shopIntro = trim($_POST['shop_intro'] ?? '');
        $isFirstStore = isset($_POST['is_first_store']) ? 1 : 0;
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isRecommended = isset($_POST['is_recommended']) ? 1 : 0;
        $isPinned = isset($_POST['is_pinned']) ? 1 : 0;
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($categoryId <= 0 || $shopName === '') {
            View::render('shop/items/form', ['item' => $_POST, 'categories' => ShopCategory::all(), 'error' => '子栏目和店铺名称不能为空', 'mode' => 'create']);
            return;
        }

        $category = ShopCategory::findById($categoryId);
        if (!$category || ($category['code'] ?? '') !== 'shopping_complex') {
            $isFirstStore = 0;
        }

        ShopItem::create([
            'category_id' => $categoryId,
            'shop_name' => $shopName,
            'shop_logo' => $shopLogo,
            'shop_images' => $shopImages,
            'shop_intro' => $shopIntro,
            'is_first_store' => $isFirstStore,
            'is_recommended' => $isRecommended,
            'is_pinned' => $isPinned,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ]);

        Auth::redirect('/admin/shop/items');
    }

    public function edit(): void
    {
        Auth::requireLogin();
        ShopCategory::ensureDefaults();
        $id = (int)($_GET['id'] ?? 0);
        $item = $id ? ShopItem::findById($id) : null;
        if (!$item) {
            Auth::redirect('/admin/shop/items');
        }
        $categories = ShopCategory::all();
        View::render('shop/items/form', ['item' => $item, 'categories' => $categories, 'error' => null, 'mode' => 'edit']);
    }

    public function update(): void
    {
        Auth::requireLogin();
        ShopCategory::ensureDefaults();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            View::render('shop/items/form', ['item' => $_POST, 'categories' => ShopCategory::all(), 'error' => '安全校验失败', 'mode' => 'edit']);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $item = $id ? ShopItem::findById($id) : null;
        if (!$item) {
            Auth::redirect('/admin/shop/items');
        }

        $categoryId = (int)($_POST['category_id'] ?? 0);
        $shopName = trim($_POST['shop_name'] ?? '');
        $shopLogo = trim($_POST['shop_logo'] ?? '');
        $shopImages = trim($_POST['shop_images'] ?? '');
        $shopIntro = trim($_POST['shop_intro'] ?? '');
        $isFirstStore = isset($_POST['is_first_store']) ? 1 : 0;
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isRecommended = isset($_POST['is_recommended']) ? 1 : 0;
        $isPinned = isset($_POST['is_pinned']) ? 1 : 0;
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($categoryId <= 0 || $shopName === '') {
            View::render('shop/items/form', ['item' => $_POST, 'categories' => ShopCategory::all(), 'error' => '子栏目和店铺名称不能为空', 'mode' => 'edit']);
            return;
        }

        $category = ShopCategory::findById($categoryId);
        if (!$category || ($category['code'] ?? '') !== 'shopping_complex') {
            $isFirstStore = 0;
        }

        ShopItem::update($id, [
            'category_id' => $categoryId,
            'shop_name' => $shopName,
            'shop_logo' => $shopLogo,
            'shop_images' => $shopImages,
            'shop_intro' => $shopIntro,
            'is_first_store' => $isFirstStore,
            'is_recommended' => $isRecommended,
            'is_pinned' => $isPinned,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ]);

        Auth::redirect('/admin/shop/items');
    }

    public function destroy(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            Auth::redirect('/admin/shop/items');
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            ShopItem::delete($id);
        }
        Auth::redirect('/admin/shop/items');
    }
}
