<?php
class FoodApiController
{
    public function cover(): void
    {
        FoodCategory::ensureDefaults();
        $categories = FoodCategory::all();
        $payload = [];

        foreach ($categories as $category) {
            $items = FoodItem::listCoverByCategory((int)$category['id'], 3);
            $payload[] = [
                'id' => (int)$category['id'],
                'code' => $category['code'],
                'name' => $category['name'],
                'items' => $items,
            ];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['categories' => $payload], JSON_UNESCAPED_UNICODE);
    }

    public function list(): void
    {
        FoodCategory::ensureDefaults();
        $categoryId = (int)($_GET['category_id'] ?? 0);
        $categoryCode = trim($_GET['category_code'] ?? '');
        $limit = (int)($_GET['limit'] ?? 20);
        $offset = (int)($_GET['offset'] ?? 0);
        $category = null;

        if ($categoryId <= 0 && $categoryCode !== '') {
            $category = FoodCategory::findByCode($categoryCode);
            $categoryId = $category ? (int)$category['id'] : 0;
        }

        if ($categoryId <= 0) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => '缺少子栏目参数'], JSON_UNESCAPED_UNICODE);
            return;
        }

        if (!$category) {
            $category = FoodCategory::findById($categoryId);
        }

        if (!$category) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => '子栏目不存在'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $items = FoodItem::listForCategory($categoryId, max(1, $limit), max(0, $offset));
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'categories' => [[
                'id' => (int)$category['id'],
                'code' => $category['code'],
                'name' => $category['name'],
                'items' => $items,
            ]],
        ], JSON_UNESCAPED_UNICODE);
    }

    public function detail(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => '缺少内容参数'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $item = FoodItem::findById($id);
        if (!$item || (int)$item['is_active'] !== 1) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => '内容不存在'], JSON_UNESCAPED_UNICODE);
            return;
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['item' => $item], JSON_UNESCAPED_UNICODE);
    }
}
