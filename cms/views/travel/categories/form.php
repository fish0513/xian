<?php
$isEdit = ($mode ?? '') === 'edit';
$base = $GLOBALS['config']['app']['base_url'] ?? '';
$category = $category ?? [];
?>
<div class="max-w-2xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                <?php echo $isEdit ? '编辑子栏目' : '新增子栏目'; ?>
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                <?php echo $isEdit ? '修改子栏目信息' : '创建新的子栏目'; ?>
            </p>
        </div>
        <a href="<?php echo View::e($base); ?>/admin/travel/categories"
            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            返回列表
        </a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700"><?php echo View::e($error); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <form method="post" action="<?php echo View::e($base); ?><?php echo $isEdit ? '/admin/travel/categories/edit' : '/admin/travel/categories/create'; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo View::e(Auth::csrfToken()); ?>">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo View::e((string)$category['id']); ?>">
            <?php endif; ?>

            <div class="px-4 py-6 sm:p-8">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <label for="code" class="block text-sm font-medium leading-6 text-gray-900">栏目编码</label>
                        <div class="mt-2">
                            <input id="code" name="code" type="text" required
                                value="<?php echo View::e($category['code'] ?? ''); ?>"
                                <?php echo $isEdit ? 'readonly' : ''; ?>
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">英文标识，如 culture_scenic，创建后不可修改</p>
                    </div>

                    <div class="sm:col-span-4">
                        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">栏目名称</label>
                        <div class="mt-2">
                            <input id="name" name="name" type="text" required
                                value="<?php echo View::e($category['name'] ?? ''); ?>"
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-4">
                        <label for="sort_order" class="block text-sm font-medium leading-6 text-gray-900">排序</label>
                        <div class="mt-2">
                            <input id="sort_order" name="sort_order" type="number"
                                value="<?php echo View::e((string)($category['sort_order'] ?? 0)); ?>"
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">数字越小越靠前</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8 bg-gray-50/50 rounded-b-xl">
                <button type="button" onclick="history.back()" class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700">取消</button>
                <button type="submit" class="rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-colors">
                    保存
                </button>
            </div>
        </form>
    </div>
</div>
