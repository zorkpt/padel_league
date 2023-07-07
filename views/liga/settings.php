<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Definições'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>
<?php extract($leagueDetails); ?>
<main class="flex-grow">
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">

        <a href="/league?id=<?= $_GET['id'] ?>"
           class="mb-4 inline-block px-6 py-2 text-xs font-medium leading-6 text-center text-white uppercase transition bg-indigo-600 rounded shadow ripple hover:shadow-lg hover:bg-indigo-800 focus:outline-none">
            Voltar
        </a>
        <div class="w-full max-w-sm mx-auto p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8">
            <h2 class="text-2xl font-bold mb-4">Opções da Liga</h2>
            <form method="post">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="visibility">
                        Visibilidade da Liga:
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="visibility">
                        <option>Público</option>
                        <option>Privado</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        Descrição da Liga:
                    </label>
                    <textarea
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            name="description" id="description"><?= $leagueDetails['descricao'] ?></textarea>

                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="league-name">
                        Nome da Liga:
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           name="league-name" id="league-name" type="text" value="<?= $leagueDetails['nome'] ?>">
                </div>

                <?php if ($validMessage = SessionController::getFlash('success')): ?>
                    <div class="text-green-500 mt-2 text-sm">
                        <?php echo $validMessage; ?>
                    </div>
                <?php endif; ?>
                <?php if ($errorMessage = SessionController::getFlash('error')): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>


                <div class="flex flex-col items-center justify-between space-y-4">
                    <button class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                            type="submit">
                        Guardar Alterações
                    </button>
                    <a href="/league/confirm-delete?id=<?= $_GET['id'] ?>"
                       class="w-full text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center   "
                       role="button">
                        Apagar Liga
                    </a>

                </div>

            </form>
        </div>
    </div>

</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>


