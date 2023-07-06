<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Adicionar Jogo'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>

<main>

    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <a href="/league?id=<?= $_GET['league_id'] ?>" class="mb-4 inline-block px-6 py-2 text-xs font-medium leading-6 text-center text-white uppercase transition bg-indigo-600 rounded shadow ripple hover:shadow-lg hover:bg-indigo-800 focus:outline-none">
            Voltar
        </a>
        <div class="w-full max-w-sm mx-auto p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">

            <form class="space-y-4 md:space-y-6" method="post">
                <div>
                    <input type="hidden" name="league_id" value="<?php echo htmlspecialchars($league_id); ?>">
                    <label for="local" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Local</label>
                    <input name="local" id="local" value="<?php echo $_SESSION['old']['local'] ?>"
                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="cidade" required="">
                </div>
                <?php unset($_SESSION['old']['local']); ?>
                <?php if ($local_errors = SessionController::getFlash('local')): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?= $local_errors ?>
                    </div>
                <?php endif; ?>
                <div>
                    <label for="data_hora"
                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Data/Hora</label>
                    <input type="datetime-local" name="data_hora" id="data_hora" value="<?php echo $_SESSION['old']['data_hora'] ?>"
                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="nome@site.com" required="">
                </div>
                <?php unset($_SESSION['old']['email']); ?>
                <?php if ($email_errors = SessionController::getFlash('data_hora')): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?= $email_errors ?>
                    </div>
                <?php endif; ?>

                <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Criar Jogo
                </button>
            </form>
        </div>
    </div>
</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>
