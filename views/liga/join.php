<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Junta a Liga'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <!-- Verificando se existe alguma mensagem de erro -->
        <?php if (Session::getFlash('league_join_error')): ?>
            <div class="error-message">
                <?php echo Session::getFlash('league_join_error'); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/league/join">

            <div class="col-span-full">
                <label for="invite_code" class="block text-sm font-medium leading-6 text-gray-900">Código de Convite:</label>
                <div class="mt-2">
                    <textarea id="invite_code" name="invite_code" rows="3"
                              placeholder="AAAAA"
                              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                </div>

            </div>


            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancelar
                </button>
                <button type="submit"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Entrar
                </button>
            </div>

            <div id="error-message" style="display: none; color: red;">
                Ocorreu um erro ao enviar o formulário. Por favor, tente novamente.
            </div>

    </div>
</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>



