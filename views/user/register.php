<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Registo'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>
<?php SessionController::start(); ?>

<main class="flex-grow">
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">

        <div class="w-full max-w-sm mx-auto p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8">

            <form class="space-y-4 md:space-y-6" method="post">
                <div>
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Nome de
                        Utilizador</label>
                    <input name="username" id="username" value="<?php echo $_SESSION['old']['username'] ?>"
                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                           placeholder="Nome" required="">
                </div>
                <?php unset($_SESSION['old']['username']); ?>
                <?php if ($username_errors = SessionController::getFlash('username')): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?= $username_errors ?>
                    </div>
                <?php endif; ?>
                <div>
                    <label for="email"
                           class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                    <input type="email" name="email" id="email" value="<?php echo $_SESSION['old']['email'] ?>"
                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                           placeholder="nome@site.com" required="">
                </div>
                <?php unset($_SESSION['old']['email']); ?>
                <?php if ($email_errors = SessionController::getFlash('email')): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?= $email_errors ?>
                    </div>
                <?php endif; ?>
                <div>
                    <label for="password"
                           class="block mb-2 text-sm font-medium text-gray-900">Senha</label>
                    <input type="password" name="password" id="password" placeholder="••••••••"
                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                           required="">
                </div>
                <div>
                    <label for="confirm-password" class="block mb-2 text-sm font-medium text-gray-900">Confirmar
                        Senha</label>
                    <input type="password" name="confirm-password" id="confirm-password" placeholder="••••••••"
                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                           required="">
                </div>
                <?php if ($password_errors = SessionController::getFlash('password')): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?= $password_errors ?>
                    </div>
                <?php endif; ?>

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" aria-describedby="terms" type="checkbox"
                               class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300"
                               required="">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="font-light text-gray-500">Aceito os <a
                                    class="font-medium text-primary-600 hover:underline" href="#">Termos
                                e condições</a></label>
                    </div>
                </div>
                <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Criar Conta
                </button>
                <p class="text-sm font-light text-gray-500">
                    Já tens uma conta? <a href="/login"
                                          class="font-medium text-primary-600 hover:underline">Entra
                        aqui</a>
                </p>
            </form>
        </div>
    </div>
</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>



