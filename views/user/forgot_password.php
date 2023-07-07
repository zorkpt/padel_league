<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Recuperar Senha'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>
<?php session_start() ?>
<main class="flex-grow">


    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">

        <div class="w-full max-w-sm mx-auto p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8">

            <form class="space-y-4 md:space-y-6" method="post">
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Endereço de
                        E-mail</label>
                    <input name="email" id="email"
                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                           placeholder="nome@site.com" required="">
                </div>
                <?php if ($errorMessage = SessionController::getFlash('resetPassword')): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Recuperar Senha
                </button>
            </form>

        </div>
    </div>
</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>



