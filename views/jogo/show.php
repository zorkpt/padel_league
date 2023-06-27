<?php require  BASE_PATH . "/views/partials/head.php"; ?>
<?php require  BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Jogo'; ?>

<?php require BASE_PATH . "/views/partials/banner.php"; ?>
<?php extract($players); ?>
<?php extract($playerIds); ?>
<?php $currentUserId = $_SESSION['user']['id']; ?>


<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
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

            <?php if ($game['status'] == GAME_LOCKED): ?>
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
            <div class="mt-4">
                <h2 class="text-lg font-medium text-gray-900">TEAM 1 (Score: <?= $game['team1_score'] ?>):</h2>
                <ul class="mt-2">
                    <?php foreach ($players as $player): ?>
                        <?php if ($player['equipa'] == 1): ?>
                            <li><?= $player['nome_utilizador'] ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="mt-4">
                <h2 class="text-lg font-medium text-gray-900">TEAM 2 (Score: <?= $game['team2_score'] ?>):</h2>
                <ul class="mt-2">
                    <?php foreach ($players as $player): ?>
                        <?php if ($player['equipa'] == 2): ?>
                            <li><?= $player['nome_utilizador'] ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>


        <?php if ($game['status'] == GAME_OPEN): ?>
            <div class="mt-4">
                <h2 class="text-lg font-medium text-gray-900">Jogadores inscritos:</h2>
                <ul class="mt-2">
                    <?php foreach ($players as $player): ?>
                        <li><?= $player['nome_utilizador'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>
