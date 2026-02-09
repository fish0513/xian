<?php
class FoodItemController
{
    public function index(): void
    {
        Auth::requireLogin();
        FoodCategory::ensureDefaults();
        $categories = FoodCategory::all();
        $categoryId = (int)($_GET['category_id'] ?? 0);
        $pageSize = 20;
        $page = (int)($_GET['page'] ?? 1);
        if ($page < 1) {
            $page = 1;
        }
        $filterCategoryId = $categoryId > 0 ? $categoryId : null;
        $total = FoodItem::countWithCategory($filterCategoryId);
        $totalPages = (int)max(1, (int)ceil($total / $pageSize));
        if ($page > $totalPages) {
            $page = $totalPages;
        }
        $offset = ($page - 1) * $pageSize;
        $items = FoodItem::allWithCategory($filterCategoryId, $pageSize, $offset);
        View::render('food/items/index', [
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
        FoodCategory::ensureDefaults();
        $categories = FoodCategory::all();
        View::render('food/items/form', ['item' => null, 'categories' => $categories, 'error' => null, 'mode' => 'create']);
    }

    public function store(): void
    {
        Auth::requireLogin();
        FoodCategory::ensureDefaults();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            View::render('food/items/form', ['item' => null, 'categories' => FoodCategory::all(), 'error' => '安全校验失败', 'mode' => 'create']);
            return;
        }

        $categoryId = (int)($_POST['category_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $coverUrl = trim($_POST['cover_url'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $businessHours = trim($_POST['business_hours'] ?? '');
        $recommendScore = (int)($_POST['recommend_score'] ?? 0);
        $latitude = trim($_POST['latitude'] ?? '');
        $longitude = trim($_POST['longitude'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isRecommended = isset($_POST['is_recommended']) ? 1 : 0;
        $isPinned = isset($_POST['is_pinned']) ? 1 : 0;
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($categoryId <= 0 || $title === '') {
            View::render('food/items/form', ['item' => $_POST, 'categories' => FoodCategory::all(), 'error' => '子栏目和标题不能为空', 'mode' => 'create']);
            return;
        }

        FoodItem::create([
            'category_id' => $categoryId,
            'title' => $title,
            'subtitle' => $subtitle,
            'cover_url' => $coverUrl,
            'content' => $content,
            'address' => $address,
            'phone' => $phone,
            'business_hours' => $businessHours,
            'recommend_score' => $recommendScore,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'is_recommended' => $isRecommended,
            'is_pinned' => $isPinned,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ]);

        Auth::redirect('/admin/food/items');
    }

    public function edit(): void
    {
        Auth::requireLogin();
        FoodCategory::ensureDefaults();
        $id = (int)($_GET['id'] ?? 0);
        $item = $id ? FoodItem::findById($id) : null;
        if (!$item) {
            Auth::redirect('/admin/food/items');
        }
        $categories = FoodCategory::all();
        View::render('food/items/form', ['item' => $item, 'categories' => $categories, 'error' => null, 'mode' => 'edit']);
    }

    public function update(): void
    {
        Auth::requireLogin();
        FoodCategory::ensureDefaults();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            View::render('food/items/form', ['item' => $_POST, 'categories' => FoodCategory::all(), 'error' => '安全校验失败', 'mode' => 'edit']);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $item = $id ? FoodItem::findById($id) : null;
        if (!$item) {
            Auth::redirect('/admin/food/items');
        }

        $categoryId = (int)($_POST['category_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $coverUrl = trim($_POST['cover_url'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $businessHours = trim($_POST['business_hours'] ?? '');
        $recommendScore = (int)($_POST['recommend_score'] ?? 0);
        $latitude = trim($_POST['latitude'] ?? '');
        $longitude = trim($_POST['longitude'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isRecommended = isset($_POST['is_recommended']) ? 1 : 0;
        $isPinned = isset($_POST['is_pinned']) ? 1 : 0;
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($categoryId <= 0 || $title === '') {
            View::render('food/items/form', ['item' => $_POST, 'categories' => FoodCategory::all(), 'error' => '子栏目和标题不能为空', 'mode' => 'edit']);
            return;
        }

        FoodItem::update($id, [
            'category_id' => $categoryId,
            'title' => $title,
            'subtitle' => $subtitle,
            'cover_url' => $coverUrl,
            'content' => $content,
            'address' => $address,
            'phone' => $phone,
            'business_hours' => $businessHours,
            'recommend_score' => $recommendScore,
            'latitude' => $latitude !== '' ? $latitude : null,
            'longitude' => $longitude !== '' ? $longitude : null,
            'is_recommended' => $isRecommended,
            'is_pinned' => $isPinned,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ]);

        Auth::redirect('/admin/food/items');
    }

    public function destroy(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            Auth::redirect('/admin/food/items');
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            FoodItem::delete($id);
        }
        Auth::redirect('/admin/food/items');
    }
}
