<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Junta a Liga'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>

<main class="flex-grow">

    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <div class="w-full max-w-sm mx-auto p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8">
            <form method="POST" class="space-y-6" action="/league/join">
                <div>
                    <label for="invite_code" class="block text-sm font-medium leading-6 text-gray-900">Código
                        de Convite:</label>

                    <input id="invite_code" name="invite_code" placeholder="ABCDE"
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                    <?php if ($leagueJoinError = SessionController::getFlash('league_join_error')): ?>
                        <div class="text-red-500 mt-2 text-sm">
                            <?php echo $leagueJoinError ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <button type="submit"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Entrar
                    </button>
                </div>


            </form>
        </div>
    </div>
</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>



