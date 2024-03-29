<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Criar Liga'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>

<main class="flex-grow">

    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <a href="/dashboard" class="mb-4 inline-block px-6 py-2 text-xs font-medium leading-6 text-center text-white uppercase transition bg-indigo-600 rounded shadow ripple hover:shadow-lg hover:bg-indigo-800 focus:outline-none">
            Voltar
        </a>
        <div class="w-full max-w-sm mx-auto p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8">

            <form class="space-y-4 md:space-y-6" method="post">
                <div>
                    <input type="hidden" name="league_id" value="<?php echo htmlspecialchars($league_id); ?>">
                    <label for="league" class="block mb-2 text-sm font-medium text-gray-900">Nome da Liga</label>
                    <input name="league" id="league" value="<?php if(isset($_SESSION['old']['league'])) echo $_SESSION['old']['league'] ?>"
                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                           placeholder="nome" required="">
                </div>
                <?php unset($_SESSION['old']['league']); ?>
                <?php if ($league_errors = SessionController::getFlash('league_error')): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?= $league_errors ?>
                    </div>
                <?php endif; ?>
                <div>

                    <label for="tipo_liga" class="block mb-2 text-sm font-medium text-gray-900">
                        Tipo de Liga
                    </label>
                        <select name="league_type">
                            <option value="publica">Publica</option>
                            <option value="privada">Privada</option>
                        </select>
                    <label for="descricao"
                           class="block mb-2 mt-2 text-sm font-medium text-gray-900">Descrição</label>
                    <textarea name="descricao" id="descricao"
                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                              placeholder="Breve descrição da Liga" required=""> </textarea>
                </div>
                <?php unset($_SESSION['old']['descricao']); ?>

                <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Criar Liga
                </button>
            </form>
        </div>
    </div>
</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>



