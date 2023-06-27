<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Adicionar Jogo'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <form method="post">
            <div class="sm:col-span-4">
                <input type="hidden" name="league_id" value="<?php echo htmlspecialchars($league_id); ?>">

                <label for="local" class="block text-sm font-medium leading-6 text-gray-900">Local do Jogo:</label>
                <div class="mt-2">
                    <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                        <input type="text" name="local" id="local"
                               class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                               placeholder="Local do Jogo" required>
                    </div>
                </div>
            </div>

            <div class="col-span-full">
                <label for="data_hora" class="block text-sm font-medium leading-6 text-gray-900">Data e Hora do Jogo:</label>
                <div class="mt-2">
                    <input type="datetime-local" id="data_hora" name="data_hora"
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancelar</button>
                <button type="submit"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Adicionar
                </button>
            </div>

            <div id="error-message" style="display: none; color: red;">
                Ocorreu um erro ao enviar o formulário. Por favor, tente novamente.
            </div>
        </form>
    </div>
</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>
