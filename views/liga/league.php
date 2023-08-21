<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php extract($leagueDetails);
extract($leagueMembers);
extract($ongoingLeagueGames);
extract($lastFiveGames);
extract($ranking);
extract($joinRequests);
?>
<?php extract($openLeagueGames); ?>
<?php $header = $leagueDetails['nome'] ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>

<div class="mb-auto">

    <main>

        <div class="space-y-4 mx-auto h-300px max-w-7xl py-6 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!--League Info-->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <div class="space-y-4 bg-white p-6 rounded-xl shadow-md">
                        <!-- Título da Liga -->
                        <div class="text-xl font-semibold text-indigo-600 border-b border-gray-200 pb-2">
                            Liga <?= htmlspecialchars($leagueDetails['tipo']) ?>
                        </div>

                        <!-- Informações do criador da Liga -->
                        <div class="flex items-center space-x-4 mt-2">
                            <img class="w-12 h-12 rounded-full" src="<?= $leagueDetails['avatar'] ?>"
                                 alt="<?= $leagueDetails['nome_utilizador'] ?>'s avatar">
                            <div class="text-sm text-gray-600">
                                Organizada por: <span class="font-medium"><?= $leagueDetails['nome_utilizador'] ?></span>
                            </div>
                        </div>

                        <!-- Data de criação -->
                        <div class="text-sm text-gray-500 mt-2">
                            Criada em: <?= htmlspecialchars((new DateTime($leagueDetails['data_criacao']))->format("d/m/Y")) ?>
                        </div>

                        <!-- Descrição da Liga -->
                        <p class="mt-2 text-gray-700">
                            <?= htmlspecialchars($leagueDetails['descricao']) ?>
                        </p>
                    </div>

                </div>

                <!-- Create Game and Invite Code -->
                <?php if($isVisitor): ?>
                    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6 md:col-span-1 space-y-3">
                        <h2 class="text-2xl font-bold text-center mb-4">Visitante</h2>
                        <p class="text-sm font-medium text-gray-700 text-center">A ver esta liga como visitante.</p>
                        <div class="flex justify-center">
                            <a class="unsubscribe-button inline-block mt-4 rounded-md bg-green-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600" href="/league/request?liga=<?= $leagueDetails['id'] ?>">Enviar Pedido</a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(!$isVisitor): ?>
                <div class="bg-white shadow-md rounded-lg overflow-hidden p-4 md:col-span-1">
                    <?php if ($message = SessionController::getFlash('league_settings_success')): ?>
                        <div class="text-green-500 mt-2 text-sm">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                    <h2 class="text-2xl font-bold mb-2">Gestão</h2>
                    <div class="overflow-y-auto max-h-[300px]">
                        <a href="/game/create?league_id=<?php echo $_GET['id']; ?>"
                           class="mb-4 inline-block px-6 py-2 text-xs font-medium leading-6 text-center text-white uppercase transition bg-indigo-600 rounded shadow ripple hover:shadow-lg hover:bg-indigo-800 focus:outline-none">
                            Criar Jogo
                        </a>

                        <?php if ($_SESSION['user']['id'] == $leagueDetails['id_criador']): ?>
                            <a href="/league/settings?id=<?php echo $_GET['id']; ?>"
                               class="mb-4 inline-block px-6 py-2 text-xs font-medium leading-6 text-center text-white uppercase transition bg-orange-600 rounded shadow ripple hover:shadow-lg hover:bg-orange-800 focus:outline-none">
                                Definições
                            </a>
                        <?php endif; ?>

                        <div class="mt-2">
                            <label for="invite-code" class="block text-sm font-medium text-gray-700">Código de
                                convite</label>
                            <input type="text" name="invite-code" id="invite-code"
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                   value="<?php echo $inviteCode['codigo_convite']; ?>" readonly>
                        </div>

                        <div class="mt-2">
                            <form method="post">
                                <label for="email" class="block text-sm font-medium text-gray-700">Convidar por
                                    E-Mail</label>
                                <input type="text" name="email" id="email"
                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <button type="submit"
                                        class="mt-2 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                                    Enviar Convite
                                </button>
                            </form>
                        </div>
                        <?php if ($email_errors = SessionController::getFlash('error')): ?>
                            <div class="text-red-500 mt-2 text-sm">
                                <?= $email_errors ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($email_success = SessionController::getFlash('success')): ?>
                            <div class="text-green-500 mt-2 text-sm">
                                <?= $email_success ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Last 5 Matches -->
                <div class="h-[300px] md:col-span-2 bg-white p-4 rounded-lg shadow-md overflow-y-auto">
                    <h2 class="text-2xl font-bold mb-2">Ultimos Jogos Terminados</h2>

                    <?php foreach ($lastFiveGames as $game): ?>
                        <!--Game Card-->
                        <div class="mb-4 bg-gray-100 border rounded-lg p-3 shadow-sm">
                            <p class="font-bold text-lg mb-1"><?= $game['local'] ?>
                                , <?= htmlspecialchars((new DateTime($game['data_hora']))->format("d/m/Y")) ?></p>
                            <a class="block" href="/game?id=<?= $game['id'] ?>">
                                <div class="flex items-center justify-between">
                                    <?php
                                    if (is_array($game['players'])) {
                                        $team1 = array_filter($game['players'], function ($player) {
                                            return $player['equipa'] == 1;
                                        });
                                        $team2 = array_filter($game['players'], function ($player) {
                                            return $player['equipa'] == 2;
                                        });
                                        ?>

                                        <!--Team 1-->
                                        <div class="w-2/5 p-2 bg-white rounded shadow">
                                            <?php foreach ($team1 as $player): ?>
                                                <div class="flex items-center">
                                                    <img class="h-8 w-8 rounded-full mr-2"
                                                         src="<?= $player['avatar'] ?>"
                                                         alt="Avatar de
                                                        <?= htmlspecialchars($player['nome_utilizador']) ?>">
                                                    <span><?= htmlspecialchars($player['nome_utilizador']) ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>

                                        <!--Score-->
                                        <div class="mx-2">
                                            <span class="text-xl"><?= $game['team1_score'] ?> - <?= $game['team2_score'] ?></span>
                                        </div>

                                        <!--Team 2-->
                                        <div class="w-2/5 p-2 bg-white rounded shadow">
                                            <?php foreach ($team2 as $player): ?>
                                                <div class="flex items-center">
                                                    <img class="h-8 w-8 rounded-full mr-2"
                                                         src="<?= $player['avatar'] ?>"
                                                         alt="Avatar de
                                                         <?= htmlspecialchars($player['nome_utilizador']) ?>">
                                                    <span><?= htmlspecialchars($player['nome_utilizador']) ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php
                                    } else {
                                        echo "<p>Sem jogadores.</p>";
                                    }
                                    ?>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php require BASE_PATH . '/views/partials/rankings.php'; ?>
            <?php require BASE_PATH . '/views/partials/open_games.php'; ?>
            <?php require BASE_PATH . '/views/partials/ongoing_games.php'; ?>
            <?php require BASE_PATH . '/views/partials/league_members.php'; ?>

            <?php if($_SESSION['user']['id'] == $leagueDetails['id_criador']): ?>
            <div class="h-[300px] md:col-span-2 bg-white p-4 rounded-lg shadow-md overflow-y-auto">
                <h2 class="text-2xl font-bold mb-2">Pedidos para se Juntar</h2>

                <?php if (count($joinRequests) == 0): ?>
                    <p>Não existem pedidos para se juntar à liga.</p>
                <?php else: ?>
                <?php require BASE_PATH . '/views/partials/join_requests.php'; ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>


    </main>


<?php require BASE_PATH . "/views/partials/footer.php"; ?>



