<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Registo'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>
<?php extract($leagueData); ?>
<?php SessionController::start(); ?>



<main class="flex-grow">
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">

        <div class="w-full max-w-sm mx-auto p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8">

            <div class="mb-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h2 class="font-bold text-lg"><?= htmlspecialchars($leagueData['nome']) ?></h2>
                <p class="text-sm text-gray-600"><?= htmlspecialchars($leagueData['descricao']) ?></p>
                <p class="text-xs text-gray-400">Criado em: <?= htmlspecialchars((new DateTime($leagueData['data_criacao']))->format("d/m/Y")) ?></p>
            </div>

            <form class="space-y-4 md:space-y-6" method="post">
                <div>
                    <label for="message" class="block mb-2 text-sm font-medium text-gray-900">Escreve uma mensagem para te juntares</label>
                    <input name="message" id="message"
                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                           placeholder="Mensagem" required="">
                </div>
                <?php if ($request_errors = SessionController::getFlash('request_errors')): ?>
                    <div class="text-red-500 mt-2 text-sm">
                        <?= $request_errors ?>
                    </div>
                <?php endif; ?>
                <input type="hidden" name="league_id" value="<?= $leagueData['id'] ?>">
                <input type="hidden" name="user_id" value="<?= $_SESSION['user']['id'] ?>">
                <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Enviar Pedido
                </button>
            </form>
        </div>

    </div>
</main>