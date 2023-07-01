<?php require  BASE_PATH . "/views/partials/head.php"; ?>
<?php require  BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Jogo'; ?>

<?php require BASE_PATH . "/views/partials/banner.php"; ?>
<?php extract($players); ?>
<?php extract($playerIds); ?>
<?php $currentUserId = $_SESSION['user']['id']; ?>


<main>

    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <a href="/league?id=<?= $game['id_liga'] ?>" class="mb-4 inline-block px-6 py-2 text-xs font-medium leading-6 text-center text-white uppercase transition bg-indigo-600 rounded shadow ripple hover:shadow-lg hover:bg-indigo-800 focus:outline-none">
            Voltar
        </a>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Local: <?php echo $game['local']; ?>
                </h3>
                <p class="mt-2 text-sm text-gray-500">
                    Data: <?php echo date("d/m/Y H:i", strtotime($game['data_hora'])); ?>
                </p>
                <p class="mt-2 text-sm text-gray-500">
                    Status: <?php if ($game['status'] == 1) {
                        echo "Aberto";
                    } else if ($game['status'] == 0) {
                        echo "Terminado";
                    } else echo "A Decorrer...";

                    ?>
                </p>


            </div>

        </div>

        <!-- Botões de ação -->
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <?php if ($game['status'] == GAME_OPEN): ?>
                <?php if (!in_array($currentUserId, $playerIds)): ?>
                    <?php if (count($playerIds) < MAX_PLAYERS): ?>
                        <a class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                           href="game/subscribe?id=<?php echo $game['id']; ?>">Inscrever-se</a>
                    <?php else: ?>
                        <div>Jogo cheio</div>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="/game/unsubscribe?id=<?php echo $game['id']; ?>"
                       class="unsubscribe-button rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600">Cancelar
                        inscrição</a>
                <?php endif; ?>
            <?php endif; ?>
            <!-- Verifique se o jogo pode ser trancado antes de exibir este botão -->
            <?php if ($game['status'] == GAME_OPEN): ?>
                <?php if (count($playerIds) == MAX_PLAYERS): ?>
                    <a href="/game/lock?game_id=<?= $game['id'] ?>"
                       class="unsubscribe-button rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600">Trancar
                        Jogo</a>
                <?php else: ?>
                    <div class="lock-button cursor-not-allowed opacity-50">Trancar Jogo</div>
                <?php endif; ?>
            <?php endif; ?>


            <?php if ($game['status'] == GAME_LOCKED && !$resultsExist): ?>
                <a href="/game/register_results?id=<?php echo $game['id']; ?>" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Registrar Resultados</a>
            <?php else: ?>
                <div class="cursor-not-allowed opacity-50">Registrar Resultados</div>
            <?php endif; ?>


            <?php if ($game['status'] == GAME_LOCKED && $resultsExist): ?>
                <form action="/game/finish" method="POST">
                    <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
                    <input type="submit" value="Terminar Jogo" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                </form>
            <?php else: ?>
                <div class="cursor-not-allowed opacity-50">Terminar Jogo</div>
            <?php endif; ?>


        </div>

        <?php if ($game['status'] == GAME_LOCKED || $game['status'] == GAME_FINISHED): ?>
            <div class="flex justify-center items-center">
                <!-- Team 1 -->
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col">
                    <h2 class="mb-4 text-xl font-bold text-center text-gray-700">TEAM 1:</h2>
                    <ul class="mb-4 text-gray-700 text-center">
                        <?php foreach ($players as $player): ?>
                            <?php if ($player['equipa'] == 1): ?>
                                <li class="flex items-center justify-center">
                                    <img src="<?= $player['avatar'] ?>" alt="Avatar" class="w-8 h-8 rounded-full mr-2">
                                    <?= $player['nome_utilizador'] ?>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Score -->
                <div class="mx-4 bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col">
                    <h2 class="mb-4 text-xl font-bold text-center text-gray-700">Score:</h2>
                    <div class="text-center text-gray-700"><?= $game['team1_score'] ?> - <?= $game['team2_score'] ?></div>
                </div>

                <!-- Team 2 -->
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col">
                    <h2 class="mb-4 text-xl font-bold text-center text-gray-700">TEAM 2:</h2>
                    <ul class="mb-4 text-gray-700 text-center">
                        <?php foreach ($players as $player): ?>
                            <?php if ($player['equipa'] == 2): ?>
                                <li class="flex items-center justify-center">
                                    <img src="<?= $player['avatar'] ?>" alt="Avatar" class="w-8 h-8 rounded-full mr-2">
                                    <?= $player['nome_utilizador'] ?>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($game['status'] == GAME_OPEN): ?>
            <div class="mt-4 flex justify-center items-center">
                <!-- Registered Players -->
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col">
                    <h2 class="mb-4 text-xl font-bold text-center text-gray-700">Jogadores inscritos:</h2>
                    <ul class="mb-4 text-gray-700 text-center">
                        <?php foreach ($players as $player): ?>
                            <li class="flex items-center justify-center">
                                <img src="<?= $player['avatar'] ?>" alt="Avatar" class="w-8 h-8 rounded-full mr-2">
                                <?= $player['nome_utilizador'] ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>
