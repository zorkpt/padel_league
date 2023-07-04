<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Registo'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>
<?php SessionController::start(); ?>

<main>

    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <form method="post" enctype="multipart/form-data">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <p class="mt-1 text-sm leading-6 text-gray-600">Regista-te e entra na ação!</p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        <div class="sm:col-span-4">
                            <label for="username"
                                   class="block text-sm font-medium leading-6 text-gray-900">Nome de Utilizador</label>
                            <div class="mt-2">
                                <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                    <span class="flex select-none items-center pl-3 text-gray-500 sm:text-sm">liga-padel.pt/</span>
                                    <input type="text" name="username" id="username" autocomplete="username" value="<?php echo $_SESSION['old']['username'] ?? ''; ?>"
                                           class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                           placeholder="nomedeutilizador">
                                </div>
                            </div>
                            <?php if ($username_errors = SessionController::getFlash('username')): ?>
                                <div class="text-red-500 mt-2 text-sm">
                                    <?= $username_errors ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="sm:col-span-4">
                            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Endereço de
                                E-Mail</label>

                            <div class="mt-2">
                                <input id="email" name="email" type="email" autocomplete="email" value="<?php echo $_SESSION['old']['email'] ?? ''; ?>"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <?php if ($email_errors = SessionController::getFlash('email')): ?>
                                <div class="text-red-500 mt-2 text-sm">
                                    <?= $email_errors ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="sm:col-span-4">
                            <label for="password"
                                   class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                            <div class="mt-2">
                                <input id="password" name="password" type="password" autocomplete="current-password"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <?php if ($password_errors = SessionController::getFlash('password')): ?>
                                <div class="text-red-500 mt-2 text-sm">
                                    <?= $password_errors ?>
                                </div>
                            <?php endif; ?>


                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <button type="submit"
                                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    Criar Conta
                                </button>
                            </div>

        </form>

    </div>


</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>



