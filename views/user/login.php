<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php session_start(); ?>
<?php $header = 'Login'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>

<main class="flex-grow">
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">

        <div class="w-full max-w-sm mx-auto p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8">
            <form class="space-y-6" method="post">
                <div>
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome de
                        Utilizador</label>
                    <input name="username" id="username" value="<?php echo $_SESSION['old']['username'] ?>"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                           placeholder="nomedeutilizador" required>
                </div>
                <?php unset($_SESSION['old']['user']); ?>
                <div>
                    <label for="password"
                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Senha</label>
                    <input type="password" name="password" id="password" placeholder="••••••••"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                           required>
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
                <?php if ($errorMessage = SessionController::getFlash('login')): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>

                <div class="flex items-start">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="remember" type="checkbox" value=""
                                   class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800">
                        </div>
                        <label for="remember"
                               class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Lembrar</label>
                    </div>
                    <a href="/user/forgot-password"
                       class="ml-auto text-sm text-blue-700 hover:underline dark:text-blue-500">Esqueceu a Senha?</a>
                </div>
                <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Entrar na tua conta
                </button>
                <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
                    Não estás registado? <a href="/register" class="text-blue-700 hover:underline dark:text-blue-500">Criar
                        conta</a>
                </div>
            </form>
        </div>


    </div>
</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>



