<body class="flex flex-col min-h-screen h-full">
<div class="min-h-full">
    <nav class="bg-gray-800">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                       <a href="/"> <img class="h-8 w-8" src="<?= '/uploads/padel.png' ?>" alt="Liga-Padel"></a>
                    </div>
                    <div>
                        <div class="ml-10 flex items-baseline space-x-4">
                            <?php
                            $notifications = NotificationController::getUnreadByUser($_SESSION['user']['id']);
                            ?>
                            <?php if (isLoggedIn()): ?>
                                <a href="/dashboard"
                                   class="<?= uriIs('/dashboard') ? 'bg-gray-900 text-white rounded-md text-xs px-2 py-1 sm:text-sm sm:px-3 sm:py-2" aria-current="page"' : 'text-gray-300 hover:bg-gray-700 hover:text-white rounded-md text-xs px-2 py-1 sm:text-sm sm:px-3 sm:py-2' ?>">
                                    <span class="flex items-center">Dashboard</span>
                                </a>

                                <a href="/league/join"
                                   class="<?= uriIs('/league/join') ? 'bg-gray-900 text-white rounded-md text-xs px-2 py-1 sm:text-sm sm:px-3 sm:py-2" aria-current="page"' : 'text-gray-300 hover:bg-gray-700 hover:text-white rounded-md text-xs px-2 py-1 sm:text-sm sm:px-3 sm:py-2' ?>">
                                    <span class="flex items-center">Juntar a Liga</span>
                                </a>

                            <?php else: ?>
                                <a href="/register"
                                   class="<?= uriIs('/register') ? 'bg-gray-900 text-white rounded-md px-3 py-2 text-sm font-medium" aria-current="page"' : 'text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium' ?>">
                                    <span class="flex items-center">Registrar</span>
                                </a>
                                <a href="/login"
                                   class="<?= uriIs('/login') ? 'bg-gray-900 text-white rounded-md px-3 py-2 text-sm font-medium" aria-current="page"' : 'text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium' ?>">
                                    <span class="flex items-center">Login</span>
                                </a>
                            <?php endif ?>
                        </div>

                    </div>
                </div>

                <div>
                    <div class="ml-4 flex items-center md:ml-6">
                        <div class="relative">

                            <?php if (isLoggedIn()): ?>
                                <!--Notifications Button-->
                                <button
                                        class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                                        id="notification-button"
                                >
                                    <span class="sr-only">View notifications</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                         stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                                    </svg>

                                    <?php if (!empty($notifications)): ?>
                                        <span class="absolute top-0 right-0 flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full"><?= count($notifications) ?></span>
                                    <?php endif; ?>

                                </button>
                                <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                     id="notification-dropdown" style="display: none;">
                                    <div class="py-1" role="menu" aria-orientation="vertical"
                                         aria-labelledby="options-menu">
                                        <?php if (!empty($notifications)): ?>
                                            <?php foreach ($notifications as $notification): ?>
                                                <a href="/notification/read?id=<?= $notification['id'] ?>"
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                   role="menuitem"><?= htmlspecialchars($notification['content']) ?></a>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="px-4 py-2 text-sm text-gray-500">Sem notificações</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>


                        <?php if (isset($_SESSION['user'])): ?>
                            <div class="relative ml-3">
                                <input type="checkbox" id="user-menu-button" class="hidden">
                                <label for="user-menu-button"
                                       class="flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-gray-800 cursor-pointer">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="h-8 w-8 rounded-full" src="<?= $_SESSION['user']['avatar']; ?>" alt="">
                                </label>
                                <div id="user-menu"
                                     class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden"
                                >
                                    <a href="/profile?id=<?= $_SESSION['user']['id']; ?>"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200">Perfil</a>
                                    <a href="/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200">Definições</a>
                                    <a href="/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200">Sair</a>
                                </div>
                            </div>
                        <?php endif; ?>


                    </div>
                </div>
            </div>
        </div>
    </nav>