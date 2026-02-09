<?php
class TravelItemController
{
    public function index(): void
    {
        Auth::requireLogin();
        $categoryId = (int)($_GET['category_id'] ?? 0);
        $categories = TravelCategory::all();
        $pageSize = 20;
        $page = (int)($_GET['page'] ?? 1);
        if ($page < 1) {
            $page = 1;
        }
        $filterCategoryId = $categoryId > 0 ? $categoryId : null;
        $total = TravelItem::countWithCategory($filterCategoryId);
        $totalPages = (int)max(1, (int)ceil($total / $pageSize));
        if ($page > $totalPages) {
            $page = $totalPages;
        }
        $offset = ($page - 1) * $pageSize;
        $items = TravelItem::allWithCategory($filterCategoryId, $pageSize, $offset);
        View::render('travel/items/index', [
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
        $categories = TravelCategory::all();
        View::render('travel/items/form', ['item' => null, 'categories' => $categories, 'error' => null, 'mode' => 'create']);
    }

    public function store(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            $categories = TravelCategory::all();
            View::render('travel/items/form', ['item' => $_POST, 'categories' => $categories, 'error' => '安全校验失败', 'mode' => 'create']);
            return;
        }

        $categoryId = (int)($_POST['category_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');

        if ($categoryId <= 0 || $title === '') {
            $categories = TravelCategory::all();
            View::render('travel/items/form', ['item' => $_POST, 'categories' => $categories, 'error' => '请选择子栏目并填写标题', 'mode' => 'create']);
            return;
        }

        $ticketPrice = $_POST['ticket_price'] ?? '';
        if ($ticketPrice !== '' && !is_numeric($ticketPrice)) {
            $categories = TravelCategory::all();
            View::render('travel/items/form', ['item' => $_POST, 'categories' => $categories, 'error' => '门票价格必须是数字', 'mode' => 'create']);
            return;
        }

        TravelItem::create([
            'category_id' => $categoryId,
            'title' => $title,
            'subtitle' => $_POST['subtitle'] ?? '',
            'cover_url' => $_POST['cover_url'] ?? '',
            'content' => $_POST['content'] ?? '',
            'address' => $_POST['address'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'business_hours' => $_POST['business_hours'] ?? '',
            'ticket_price' => $ticketPrice,
            'latitude' => $_POST['latitude'] ?? '',
            'longitude' => $_POST['longitude'] ?? '',
            'is_recommended' => !empty($_POST['is_recommended']) ? 1 : 0,
            'is_pinned' => !empty($_POST['is_pinned']) ? 1 : 0,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'is_active' => !empty($_POST['is_active']) ? 1 : 0,
        ]);

        Auth::redirect('/admin/travel/items');
    }

    public function edit(): void
    {
        Auth::requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $item = $id ? TravelItem::findById($id) : null;
        if (!$item) {
            Auth::redirect('/admin/travel/items');
        }
        $categories = TravelCategory::all();
        View::render('travel/items/form', ['item' => $item, 'categories' => $categories, 'error' => null, 'mode' => 'edit']);
    }

    public function update(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            $categories = TravelCategory::all();
            View::render('travel/items/form', ['item' => $_POST, 'categories' => $categories, 'error' => '安全校验失败', 'mode' => 'edit']);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $item = $id ? TravelItem::findById($id) : null;
        if (!$item) {
            Auth::redirect('/admin/travel/items');
        }

        $categoryId = (int)($_POST['category_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');

        if ($categoryId <= 0 || $title === '') {
            $categories = TravelCategory::all();
            View::render('travel/items/form', ['item' => $_POST, 'categories' => $categories, 'error' => '请选择子栏目并填写标题', 'mode' => 'edit']);
            return;
        }

        $ticketPrice = $_POST['ticket_price'] ?? '';
        if ($ticketPrice !== '' && !is_numeric($ticketPrice)) {
            $categories = TravelCategory::all();
            View::render('travel/items/form', ['item' => $_POST, 'categories' => $categories, 'error' => '门票价格必须是数字', 'mode' => 'edit']);
            return;
        }

        TravelItem::update($id, [
            'category_id' => $categoryId,
            'title' => $title,
            'subtitle' => $_POST['subtitle'] ?? '',
            'cover_url' => $_POST['cover_url'] ?? '',
            'content' => $_POST['content'] ?? '',
            'address' => $_POST['address'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'business_hours' => $_POST['business_hours'] ?? '',
            'ticket_price' => $ticketPrice,
            'latitude' => $_POST['latitude'] ?? '',
            'longitude' => $_POST['longitude'] ?? '',
            'is_recommended' => !empty($_POST['is_recommended']) ? 1 : 0,
            'is_pinned' => !empty($_POST['is_pinned']) ? 1 : 0,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'is_active' => !empty($_POST['is_active']) ? 1 : 0,
        ]);

        Auth::redirect('/admin/travel/items');
    }

    public function destroy(): void
    {
        Auth::requireLogin();
        $csrf = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrf($csrf)) {
            Auth::redirect('/admin/travel/items');
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            TravelItem::delete($id);
        }
        Auth::redirect('/admin/travel/items');
    }
}
