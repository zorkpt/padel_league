<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php extract($leagueDetails);
extract($leagueMembers);
extract($ongoingLeagueGames);
extract($lastFiveGames);
extract($ranking);
?>
<?php extract($openLeagueGames); ?>
<?php $header = 'Liga ' . $leagueDetails['nome'] ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>


<main>

    <div class="mx-auto h-300px max-w-7xl py-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">


            <!--League Info-->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Informações da Liga</h2>
                <div class="space-y-3">
                    <div class="flex items-center space-x-2">
                        <p><span class="font-semibold">Criador da Liga:</span> <img class="w-8 h-8 rounded-full"
                                                                                    src="<?= $leagueDetails['avatar'] ?>"
                                                                                    alt="Avatar do criador"><?= htmlspecialchars($leagueDetails['nome_utilizador']) ?>
                        </p>
                    </div>
                    <p>
                        <span class="font-semibold">Data de Criação:</span> <?= htmlspecialchars($leagueDetails['data_criacao']) ?>
                    </p>
                    <p>
                        <span class="font-semibold">Descrição:</span> <?= htmlspecialchars($leagueDetails['descricao']) ?>
                    </p>
                </div>
            </div>

            <!-- Last 5 Matches -->
            <div class="md:col-span-2 bg-white p-4 rounded-lg shadow-md">
                <div class="bg-white shadow sm:rounded-lg p-4 max-h-[300px] overflow-auto">
                    <h2 class="text-2xl font-bold mb-2">Ultimos Jogos Terminados</h2>


                    <?php foreach ($lastFiveGames as $game): ?>


                        <!--Game Card-->
                        <div class="mb-4 bg-gray-100 border rounded-lg p-3 shadow-sm">
                            <p class="font-bold text-lg mb-1">Jogo <?= $game['id'] ?>:</p>
                            <a class="text-blue-500 underline block" href="/game?id=<?= $game['id'] ?>">
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
                                        <div class="w-1/3 p-2 bg-white rounded shadow">
                                            <?php foreach ($team1 as $player): ?>
                                                <div class="flex items-center">
                                                    <img class="h-8 w-8 rounded-full mr-2"
                                                         src="<?= $player['avatar'] ?>"
                                                         alt="Avatar de
                                                        <?= htmlspecialchars($player['nome_utilizador']) ?>">
                                                    <span class="font-bold"><?= htmlspecialchars($player['nome_utilizador']) ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>

                                        <!--Score-->
                                        <div class="mx-2">
                                            <span class="font-bold"><?= $game['team1_score'] ?> - <?= $game['team2_score'] ?></span>
                                        </div>

                                        <!--Team 2-->
                                        <div class="w-1/3 p-2 bg-white rounded shadow">
                                            <?php foreach ($team2 as $player): ?>
                                                <div class="flex items-center">
                                                    <img class="h-8 w-8 rounded-full mr-2"
                                                         src="<?= $player['avatar'] ?>"
                                                         alt="Avatar de
                                                         <?= htmlspecialchars($player['nome_utilizador']) ?>">
                                                    <span class="font-bold"><?= htmlspecialchars($player['nome_utilizador']) ?></span>
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


            <!-- Create Game and Invite Code -->
            <div class="h-300px overflow-auto md:col-span-1 bg-white p-4 rounded-lg shadow-md">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg p-4">
                    <h2 class="text-2xl font-bold mb-2">Gestão da Liga</h2>
                    <a href="/game/create?league_id=<?php echo $_GET['id']; ?>"
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Criar Jogo
                    </a>
                    <div class="mt-2">
                        <label for="invite-code" class="block text-sm font-medium text-gray-700">Código de
                            convite</label>
                        <input type="text" name="invite-code" id="invite-code"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               value="<?php echo $inviteCode['codigo_convite']; ?>" readonly>
                    </div>
                </div>
            </div>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">


            <!--Ranking-->
            <div class="bg-white shadow-md rounded-lg overflow-hidden p-4 md:col-span-2">
                <h2 class="text-2xl font-bold mb-2">Classificação da Liga</h2>
                <div class="overflow-y-auto max-h-[300px]">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pontos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jogos Jogados
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($ranking as $row): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <img class="w-8 h-8 rounded-full mr-2" src="<?= $row['avatar'] ?>"
                                             alt="<?= $row['nome_utilizador'] ?>'s avatar">
                                        <?= $row['nome_utilizador'] ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['total_pontuacao'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['jogos_jogados'] ?></td>
                            </tr>
                        <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>


            <!-- open games -->
            <div class="h-300px overflow-auto md:col-span-2 bg-white p-4 rounded-lg shadow-md">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg p-4 mt-6">
                    <h2 class="text-2xl font-bold mb-2">Jogos Abertos</h2>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data e Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Local</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jogadores inscritos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($openLeagueGames as $game) { ?>
                            <tr class="text-center">
                                <td class="px-6 py-4 items-center"><?= htmlspecialchars($game['data_hora']) ?></td>
                                <td class="px-6 py-4 items-center"><?= htmlspecialchars($game['local']) ?></td>
                                <td class="px-6 py-4 items-center">
                                    <?= count(GameController::getPlayersInGame($game['id'])) ?>/4
                                </td>
                                <td class="px-6 py-4 items-center"><a href="/game?id=
                            <?= htmlspecialchars($game['id']) ?>">Ver Jogo</a></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- ongoing games -->
            <div class="h-300px overflow-auto md:col-span-2 bg-white p-4 rounded-lg shadow-md">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg p-4 mt-6">
                    <h2 class="text-2xl font-bold mb-2">Jogos a decorrer</h2>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data e Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Local</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jogadores inscritos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($ongoingLeagueGames as $game) { ?>
                            <tr class="text-center">
                                <td class="px-6 py-4 whitespace-nowrap flex items-center"><?= htmlspecialchars($game['data_hora']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap flex items-center"><?= htmlspecialchars($game['local']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap flex items-center">
                                    <?= count(GameController::getPlayersInGame($game['id'])) ?>/4
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap flex items-center"><a href="/game?id=
                                <?= htmlspecialchars($game['id']) ?>">Ver Jogo</a></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!--Members List-->
            <div class="h-64 overflow-y-auto md:col-span-2 max-h-[300px] bg-white p-4 rounded-lg shadow-md">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg p-4 mt-6">
                    <h2 class="text-2xl font-bold mb-2">Membros da Liga</h2>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membro</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($leagueMembers as $member) { ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap flex items-center">
                                    <img class="h-8 w-8 rounded-full" src="<?= htmlspecialchars($member['avatar']) ?>" alt="<?= htmlspecialchars($member['nome_utilizador']) ?>'s avatar">
                                    <a href="/profile?id=<?= $member['id'] ?>" class="ml-4 text-sm text-gray-500"><?= htmlspecialchars($member['nome_utilizador']) ?></a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>



        </div>
    </div>

</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>



