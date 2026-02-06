<?php
class FoodFrontController
{
    public function cover(): void
    {
        View::renderPublic('food/front/cover', [
            'base' => $GLOBALS['config']['app']['base_url'] ?? '',
        ]);
    }

    public function list(): void
    {
        $categoryId = (int)($_GET['category_id'] ?? 0);
        $categoryCode = trim($_GET['category_code'] ?? '');
        View::renderPublic('food/front/list', [
            'base' => $GLOBALS['config']['app']['base_url'] ?? '',
            'categoryId' => $categoryId,
            'categoryCode' => $categoryCode,
        ]);
    }
}
