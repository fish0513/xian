<?php
$base = $GLOBALS['config']['app']['base_url'] ?? '';
$isLoggedIn = Auth::check();
?>
<!DOCTYPE html>
<html lang="zh-CN" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>最鲜启东CMS管理后台</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#4f46e5', // Indigo-600 like Strapi purple
                            600: '#4338ca',
                            700: '#3730a3',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 2px;
        }
    </style>
</head>

<body class="h-full text-gray-800 antialiased">

    <?php if ($isLoggedIn): ?>
        <div class="flex h-full">
            <!-- Sidebar -->
            <aside id="app-sidebar" class="w-64 bg-slate-900 text-white flex flex-col flex-shrink-0 transition-all duration-300 overflow-hidden">
                <!-- Logo Area -->
                <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800">
                    <div class="flex items-center gap-2 font-bold text-xl tracking-tight whitespace-nowrap">
                        <span class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center text-white text-sm flex-shrink-0">X</span>
                        <span class="transition-opacity duration-300">最鲜启东CMS</span>
                    </div>
                    <!-- Collapse Button -->
                    <button id="sidebar-collapse-btn" class="text-slate-400 hover:text-white transition-colors focus:outline-none ml-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </button>
                </div>

                <!-- Nav Links -->
                <nav class="flex-1 overflow-y-auto sidebar-scroll py-4 px-3 space-y-1">
                    <!-- Group: Content Manager -->
                    <div class="mb-6">
                        <h3 class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">内容管理</h3>

                        <!-- Food Qidong Menu Group -->
                        <div>
                            <button type="button" class="w-full group flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md text-slate-300 hover:bg-slate-800 hover:text-white transition-colors focus:outline-none" onclick="toggleMenu('menu-food')">
                                <div class="flex items-center">
                                    <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z" />
                                    </svg>
                                    美食启东
                                </div>
                                <svg id="arrow-menu-food" class="h-4 w-4 text-slate-500 transform transition-transform duration-200 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Submenu -->
                            <div id="submenu-menu-food" class="mt-1 space-y-1 pl-11">
                                <a href="<?php echo View::e($base); ?>/admin/food/categories" class="group flex items-center py-2 text-sm font-medium text-slate-400 hover:text-white transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-600 mr-2 group-hover:bg-primary-500 transition-colors"></span>
                                    美食子栏目
                                </a>
                                <a href="<?php echo View::e($base); ?>/admin/food/items" class="group flex items-center py-2 text-sm font-medium text-slate-400 hover:text-white transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-600 mr-2 group-hover:bg-primary-500 transition-colors"></span>
                                    美食内容
                                </a>
                            </div>
                        </div>

                        <!-- Travel Qidong Menu Group -->
                        <div>
                            <button type="button" class="w-full group flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md text-slate-300 hover:bg-slate-800 hover:text-white transition-colors focus:outline-none" onclick="toggleMenu('menu-travel')">
                                <div class="flex items-center">
                                    <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    潮玩启东
                                </div>
                                <svg id="arrow-menu-travel" class="h-4 w-4 text-slate-500 transform transition-transform duration-200 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Submenu -->
                            <div id="submenu-menu-travel" class="mt-1 space-y-1 pl-11">
                                <a href="<?php echo View::e($base); ?>/admin/travel/categories" class="group flex items-center py-2 text-sm font-medium text-slate-400 hover:text-white transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-600 mr-2 group-hover:bg-primary-500 transition-colors"></span>
                                    潮玩子栏目
                                </a>
                                <a href="<?php echo View::e($base); ?>/admin/travel/items" class="group flex items-center py-2 text-sm font-medium text-slate-400 hover:text-white transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-600 mr-2 group-hover:bg-primary-500 transition-colors"></span>
                                    潮玩内容
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Group: Settings -->
                    <div>
                        <h3 class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">系统设置</h3>
                        <a href="<?php echo View::e($base); ?>/admin/admins" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md bg-slate-800 text-white">
                            <svg class="mr-3 h-5 w-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            管理员管理
                        </a>
                    </div>
                </nav>

                <!-- User Footer -->
                <div class="p-4 border-t border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-bold">
                            A
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">Admin</p>
                            <p class="text-xs text-slate-500 truncate">系统管理员</p>
                        </div>
                        <form method="post" action="<?php echo View::e($base); ?>/admin/logout">
                            <input type="hidden" name="csrf_token" value="<?php echo View::e(Auth::csrfToken()); ?>">
                            <button type="submit" class="text-slate-400 hover:text-white transition-colors" title="退出">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Header (Optional, mostly for mobile toggle or breadcrumbs) -->
                <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8">
                    <div class="flex items-center gap-4">
                        <!-- Toggle Button -->
                        <button id="sidebar-toggle-btn" class="text-gray-400 hover:text-gray-600 focus:outline-none hidden">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                            </svg>
                        </button>
                        <div class="text-sm text-gray-500">
                            控制台 / <span class="text-gray-900 font-medium">当前页面</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <button class="text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>
                    </div>
                </header>

                <!-- Scrollable Content -->
                <div class="flex-1 overflow-auto bg-gray-50 p-8">
                    <?php echo $content; ?>
                </div>
            </main>
        </div>

    <?php else: ?>
        <!-- Login Page Layout (Full Screen) -->
        <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
            <?php echo $content; ?>
        </div>
    <?php endif; ?>

    <script>
        // Toggle Submenu function
        window.toggleMenu = function(id) {
            const submenu = document.getElementById('submenu-' + id);
            const arrow = document.getElementById('arrow-' + id);
            if (submenu) {
                submenu.classList.toggle('hidden');
                if (arrow) {
                    if (submenu.classList.contains('hidden')) {
                        arrow.classList.remove('rotate-180');
                    } else {
                        arrow.classList.add('rotate-180');
                    }
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('app-sidebar');
            const collapseBtn = document.getElementById('sidebar-collapse-btn');
            const toggleBtn = document.getElementById('sidebar-toggle-btn');

            function toggleSidebar() {
                if (sidebar.classList.contains('w-64')) {
                    // Hide sidebar
                    sidebar.classList.remove('w-64');
                    sidebar.classList.add('w-0');
                    // Hide collapse button (in sidebar), Show toggle button (in header)
                    if (toggleBtn) toggleBtn.classList.remove('hidden');
                } else {
                    // Show sidebar
                    sidebar.classList.remove('w-0');
                    sidebar.classList.add('w-64');
                    // Hide toggle button (in header), Show collapse button (in sidebar)
                    if (toggleBtn) toggleBtn.classList.add('hidden');
                }
            }

            if (collapseBtn) collapseBtn.addEventListener('click', toggleSidebar);
            if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
        });
    </script>
</body>

</html>