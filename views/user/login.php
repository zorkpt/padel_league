


<?php require  BASE_PATH . "/views/partials/head.php"; ?>
<?php require  BASE_PATH . "/views/partials/nav.php"; ?>
<?php session_start(); ?>
<?php $header = 'Login'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <form method="post">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                        <div class="sm:col-span-4">
                            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Nome de Utilizador</label>
                            <div class="mt-2">
                                <input id="username" name="username" autocomplete="username"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="sm:col-span-4">
                            <label for="password"
                                   class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                            <div class="mt-2">
                                <input id="password" name="password" type="password" autocomplete="current-password"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <?php if (isset($_SESSION['errors']['login'])): ?>
                                <div class="text-red-500 mt-2 text-sm">
                                    <?php echo $_SESSION['errors']['login']; ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($validMessage = SessionController::getFlash('resetPassword')): ?>
                            <div class="text-green-500 mt-2 text-sm">
                                <?php echo $validMessage; ?>
                            </div>
                            <?php endif; ?>

                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <a href="/user/forgot-password" class="text-sm font-semibold leading-6 text-gray-900">Esqueci a senha</a>
                                <button type="submit"
                                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    Entrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



    </div>
</main>

<?php require  BASE_PATH . "/views/partials/footer.php"; ?>



