<?php
$base = $GLOBALS['config']['app']['base_url'] ?? '';
?>
<div class="max-w-md w-full space-y-8">
    <div>
        <div class="mx-auto h-16 w-16 bg-primary-600 rounded-xl flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-primary-500/50">
            X
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            最鲜启东CMS
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            内容管理系统
        </p>
    </div>

    <div class="bg-white py-8 px-4 shadow-xl rounded-2xl sm:px-10 border border-gray-100">
        <?php if (!empty($error)): ?>
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
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

        <form class="space-y-6" action="<?php echo View::e($base); ?>/admin/login" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo View::e(Auth::csrfToken()); ?>">

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">
                    账号
                </label>
                <div class="mt-1">
                    <input id="username" name="username" type="text" autocomplete="username" required
                        value="<?php echo View::e($username ?? ''); ?>"
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors">
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    密码
                </label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors">
                </div>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors shadow-primary-500/30">
                    登 录
                </button>
            </div>
        </form>
    </div>
</div>