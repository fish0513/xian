<?php
$isEdit = ($mode ?? '') === 'edit';
$base = $GLOBALS['config']['app']['base_url'] ?? '';
$admin = $admin ?? [];
?>
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                <?php echo $isEdit ? '编辑管理员' : '新增管理员'; ?>
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                <?php echo $isEdit ? '修改管理员账号信息' : '创建一个新的管理员账号'; ?>
            </p>
        </div>
        <a href="<?php echo View::e($base); ?>/admin/admins"
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

    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
        <form method="post" action="<?php echo View::e($base); ?><?php echo $isEdit ? '/admin/admins/edit' : '/admin/admins/create'; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo View::e(Auth::csrfToken()); ?>">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo View::e((string)$admin['id']); ?>">
            <?php endif; ?>

            <div class="px-4 py-6 sm:p-8">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <!-- Username -->
                    <div class="sm:col-span-3">
                        <label for="username" class="block text-sm font-medium leading-6 text-gray-900">账号</label>
                        <div class="mt-2">
                            <input type="text" name="username" id="username" required
                                value="<?php echo View::e($admin['username'] ?? ''); ?>"
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="sm:col-span-3">
                        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">姓名</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name"
                                value="<?php echo View::e($admin['name'] ?? ''); ?>"
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="sm:col-span-4">
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">邮箱</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email"
                                value="<?php echo View::e($admin['email'] ?? ''); ?>"
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-4">
                        <label for="role" class="block text-sm font-medium leading-6 text-gray-900">管理员级别</label>
                        <div class="mt-2">
                            <select id="role" name="role"
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                <?php $role = $admin['role'] ?? 'normal'; ?>
                                <option value="normal" <?php echo $role === 'normal' ? 'selected' : ''; ?>>普通管理员</option>
                                <option value="super" <?php echo $role === 'super' ? 'selected' : ''; ?>>超级管理员</option>
                            </select>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="sm:col-span-4">
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">
                            <?php echo $isEdit ? '新密码（留空不改）' : '密码'; ?>
                        </label>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" <?php echo $isEdit ? '' : 'required'; ?>
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                        </div>
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
