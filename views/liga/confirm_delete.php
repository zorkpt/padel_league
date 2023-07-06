<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Junta a Liga'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>

<main>

    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">

        <div class="w-full max-w-sm mx-auto p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
            <form class="space-y-6" method="post">
                <h1 class="mb-4">Confirmação de Eliminação da Liga</h1>
                <p>Tens a certeza de que desejas eliminar esta liga? Esta ação não pode ser desfeita.</p>
                <div>
                    <label for="password"
                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Insere a tua senha para confirmação</label>
                    <input type="password" name="password" id="password" placeholder="••••••••"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                           required>
                </div>

                <?php if ($errorMessage = SessionController::getFlash('error')): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>

                <input type="hidden" name="user_id" value="<?= $user_id ?>">
                <input type="hidden" name="league_id" value="<?= $league_id ?>">
                <button type="submit"
                        class="w-full text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                    Eliminar Liga
                </button>
            </form>
        </div>


    </div>



























</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>
