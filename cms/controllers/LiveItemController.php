<?php
class LiveItemController
{
    public function index(): void
    {
        Auth::requireLogin();
        LiveCategory::ensureDefaults();
        $categories = LiveCategory::all();
        $categoryId = (int)($_GET['category_id'] ?? 0);

        $pageSize = 20;
        $page = (int)($_GET['page'] ?? 1);
        if ($page < 1) {
            $page = 1;
        }
        $filterCategoryId = $categoryId > 0 ? $categoryId : null;
        $total = LiveItem::countWithCategory($filterCategoryId);
        $totalPages = (int)max(1, (int)ceil($total / $pageSize));
        if ($page > $totalPages) {
            $page = $totalPages;
        }
        $offset = ($page - 1) * $pageSize;

        $items = LiveItem::allWithCategory($filterCategoryId, $pageSize, $offset);
        View::render('live/items/index', [
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
        LiveCategory::ensureDefaults();
        $categories = LiveCategory::all();
        View::render('live/items/form', ['item' => null, 'categories' => $categories, 'error' => null, 'mode' => 'create']);
    }

    public function store(): void
    {
        Auth::requireLogin();
        LiveCategory::ensureDefaults();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            View::render('live/items/form', ['item' => null, 'categories' => LiveCategory::all(), 'error' => '安全校验失败', 'mode' => 'create']);
            return;
        }

        $categoryId = (int)($_POST['category_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $coverUrl = trim($_POST['cover_url'] ?? '');
        $intro = trim($_POST['intro'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isRecommended = isset($_POST['is_recommended']) ? 1 : 0;
        $isPinned = isset($_POST['is_pinned']) ? 1 : 0;
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($categoryId <= 0 || $name === '') {
            View::render('live/items/form', ['item' => $_POST, 'categories' => LiveCategory::all(), 'error' => '子栏目和酒店名称不能为空', 'mode' => 'create']);
            return;
        }

        LiveItem::create([
            'category_id' => $categoryId,
            'name' => $name,
            'cover_url' => $coverUrl,
            'intro' => $intro,
            'address' => $address,
            'phone' => $phone,
            'is_recommended' => $isRecommended,
            'is_pinned' => $isPinned,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ]);

        Auth::redirect('/admin/live/items');
    }

    public function edit(): void
    {
        Auth::requireLogin();
        LiveCategory::ensureDefaults();
        $id = (int)($_GET['id'] ?? 0);
        $item = $id ? LiveItem::findById($id) : null;
        if (!$item) {
            Auth::redirect('/admin/live/items');
        }
        $categories = LiveCategory::all();
        View::render('live/items/form', ['item' => $item, 'categories' => $categories, 'error' => null, 'mode' => 'edit']);
    }

    public function update(): void
    {
        Auth::requireLogin();
        LiveCategory::ensureDefaults();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            View::render('live/items/form', ['item' => $_POST, 'categories' => LiveCategory::all(), 'error' => '安全校验失败', 'mode' => 'edit']);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $item = $id ? LiveItem::findById($id) : null;
        if (!$item) {
            Auth::redirect('/admin/live/items');
        }

        $categoryId = (int)($_POST['category_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $coverUrl = trim($_POST['cover_url'] ?? '');
        $intro = trim($_POST['intro'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isRecommended = isset($_POST['is_recommended']) ? 1 : 0;
        $isPinned = isset($_POST['is_pinned']) ? 1 : 0;
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($categoryId <= 0 || $name === '') {
            View::render('live/items/form', ['item' => $_POST, 'categories' => LiveCategory::all(), 'error' => '子栏目和酒店名称不能为空', 'mode' => 'edit']);
            return;
        }

        LiveItem::update($id, [
            'category_id' => $categoryId,
            'name' => $name,
            'cover_url' => $coverUrl,
            'intro' => $intro,
            'address' => $address,
            'phone' => $phone,
            'is_recommended' => $isRecommended,
            'is_pinned' => $isPinned,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ]);

        Auth::redirect('/admin/live/items');
    }

    public function destroy(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            Auth::redirect('/admin/live/items');
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            LiveItem::delete($id);
        }
        Auth::redirect('/admin/live/items');
    }
}
