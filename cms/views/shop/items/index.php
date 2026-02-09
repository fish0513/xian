<?php
$base = $GLOBALS['config']['app']['base_url'] ?? '';
$categoryCodeById = [];
foreach (($categories ?? []) as $c) {
    $categoryCodeById[(int)$c['id']] = $c['code'] ?? '';
}
$page = (int)($page ?? 1);
$totalPages = (int)($totalPages ?? 1);
$total = (int)($total ?? 0);
$pageSize = (int)($pageSize ?? 20);
$categoryId = (int)($categoryId ?? 0);

$buildUrl = function (int $targetPage) use ($base, $categoryId) {
    $params = ['page' => $targetPage];
    if ($categoryId > 0) {
        $params['category_id'] = $categoryId;
    }
    return View::e($base) . '/admin/shop/items?' . http_build_query($params);
};
?>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">乐购内容</h1>
            <p class="mt-1 text-sm text-gray-500">管理乐购启东内容列表</p>
        </div>
        <a href="<?php echo View::e($base); ?>/admin/shop/items/create" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-primary-500/30 transition-colors">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            新增内容
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center gap-4">
            <form method="get" action="<?php echo View::e($base); ?>/admin/shop/items" class="flex items-center gap-3">
                <label for="category_id" class="text-sm text-gray-600">子栏目</label>
                <select id="category_id" name="category_id" class="rounded-md border-gray-300 text-sm">
                    <option value="0">全部</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?php echo View::e((string)$c['id']); ?>" <?php echo ($categoryId ?? 0) == $c['id'] ? 'selected' : ''; ?>>
                            <?php echo View::e($c['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="px-3 py-2 text-sm rounded-md bg-gray-100 hover:bg-gray-200">筛选</button>
            </form>
            <div class="ml-auto text-sm text-gray-500">
                共 <?php echo View::e((string)$total); ?> 条 · 第 <?php echo View::e((string)$page); ?>/<?php echo View::e((string)$totalPages); ?> 页 · 每页 <?php echo View::e((string)$pageSize); ?> 条
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">店铺名称</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">子栏目</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">推荐</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">置顶</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">排序</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">状态</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">更新时间</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-40">操作</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($items as $i): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                #<?php echo View::e((string)$i['id']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                    <span><?php echo View::e($i['shop_name']); ?></span>
                                    <?php
                                    $code = $categoryCodeById[(int)$i['category_id']] ?? '';
                                    $isFirstStore = ($code === 'shopping_complex') && !empty($i['is_first_store']);
                                    ?>
                                    <?php if ($isFirstStore): ?>
                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-800 ring-1 ring-inset ring-amber-200">首店</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo View::e($i['category_name']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                <?php echo $i['is_recommended'] ? '是' : '否'; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                <?php echo $i['is_pinned'] ? '是' : '否'; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                <?php echo View::e((string)$i['sort_order']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                <?php echo $i['is_active'] ? '上线' : '下线'; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo View::e($i['updated_at']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="<?php echo View::e($base); ?>/admin/shop/items/edit?id=<?php echo View::e((string)$i['id']); ?>" class="text-primary-600 hover:text-primary-900 flex items-center gap-1">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        编辑
                                    </a>
                                    <form method="post" action="<?php echo View::e($base); ?>/admin/shop/items/delete" class="inline-block" onsubmit="return confirm('确定要删除这条内容吗？');">
                                        <input type="hidden" name="csrf_token" value="<?php echo View::e(Auth::csrfToken()); ?>">
                                        <input type="hidden" name="id" value="<?php echo View::e((string)$i['id']); ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-900 flex items-center gap-1">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            删除
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <p class="text-sm text-gray-500">暂无内容数据</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if ($totalPages > 1): ?>
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    第 <?php echo View::e((string)$page); ?> / <?php echo View::e((string)$totalPages); ?> 页
                </div>
                <div class="flex items-center gap-2">
                    <?php if ($page > 1): ?>
                        <a href="<?php echo $buildUrl($page - 1); ?>" class="px-3 py-2 text-sm rounded-md bg-gray-100 hover:bg-gray-200">上一页</a>
                    <?php else: ?>
                        <span class="px-3 py-2 text-sm rounded-md bg-gray-50 text-gray-400 cursor-not-allowed">上一页</span>
                    <?php endif; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="<?php echo $buildUrl($page + 1); ?>" class="px-3 py-2 text-sm rounded-md bg-gray-100 hover:bg-gray-200">下一页</a>
                    <?php else: ?>
                        <span class="px-3 py-2 text-sm rounded-md bg-gray-50 text-gray-400 cursor-not-allowed">下一页</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>