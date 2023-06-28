<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Ver Ligas'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>
<?php extract($leagueDetails);
extract($leagueMembers);
extract($ongoingLeagueGames);
extract($lastFiveGames);
extract($ranking);
?>
<?php extract($openLeagueGames); ?>


<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <a href="/dashboard">Voltar</a>
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold mt-4 mb-6"><?= $leagueDetails['nome'] ?></h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Informações da Liga -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg p-4">
                    <h2 class="text-2xl font-bold mb-2">Informações da Liga</h2>
                    <p><strong>Data de Criação:</strong> <?= htmlspecialchars($leagueDetails['data_criacao']) ?></p>
                    <p><strong>Criador da Liga:</strong> <?= htmlspecialchars($leagueDetails['id_criador']) ?></p>
                    <p><strong>Descrição:</strong> <?= htmlspecialchars($leagueDetails['descricao']) ?></p>
                </div>

                <!-- Classificação da Liga -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg p-4">
                    <h2 class="text-2xl font-bold mb-2">Classificação da Liga</h2>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Pontos</th>
                            <th>Jogos Jogados</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($ranking as $row): ?>
                            <tr>

                                <td><?= $row['nome_utilizador'] ?></td>
                                <td><?= $row['total_pontuacao'] ?></td>
                                <td><?= $row['jogos_jogados'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Jogos da Liga -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg p-4">
                    <h2 class="text-2xl font-bold mb-2">Ultimos Jogos Terminados</h2>
                    <!--                    --><?php //dd($lastFiveGames); ?>
                    <?php foreach ($lastFiveGames as $game): ?>
                        <p>
                            <strong>Jogo <?= $game['id'] ?>:</strong>
                            <a href="/game?id=<?= $game['id'] ?>">
                                <?php
                                // Verifique se 'players' é um array antes de tentar usar array_filter
                                if (is_array($game['players'])) {
                                    // Separa jogadores por equipe
                                    $team1 = array_filter($game['players'], function ($player) {
                                        return $player['equipa'] == 1;
                                    });
                                    $team2 = array_filter($game['players'], function ($player) {
                                        return $player['equipa'] == 2;
                                    });

                                    // Constrói strings de nomes de equipe
                                    $team1_names = array_column($team1, 'nome_utilizador');
                                    $team2_names = array_column($team2, 'nome_utilizador');

                                    // Imprime os nomes das equipes e as pontuações
                                    echo " - " . implode(' e ', $team1_names) . ' [' . $game['team1_score'] . ']' . ' - ' . '[' . $game['team2_score'] . ']' . " - " . implode(' e ', $team2_names);

                                } else {
                                    echo "Sem jogadores.";
                                }
                                ?>
                            </a>
                        </p>
                    <?php endforeach; ?>


                </div>

                <!-- Gerenciamento da Liga -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg p-4">
                    <h2 class="text-2xl font-bold mb-2">Gerenciamento da Liga</h2>
                    <a href="/game/create?league_id=<?php echo $_GET['id']; ?>"
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Criar Jogo
                    </a>
                    <div class="mt-2">
                        <label for="invite-code" class="block text-sm font-medium text-gray-700">Código de
                            convite</label>
                        <!--                        --><?php //dd($inviteCode); ?>
                        <input type="text" name="invite-code" id="invite-code"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               value="<?php echo $inviteCode['codigo_convite']; ?>" readonly>
                    </div>
                </div>
            </div>


            <!-- Lista de Membros -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg p-4">
                <h2 class="text-2xl font-bold mb-2">Membros da Liga</h2>
                <?php foreach ($leagueMembers as $member) { ?>
                    <p><strong><a href="/profile?id=<?= $member['id'] ?>"> <?= htmlspecialchars($member['nome_utilizador']) ?> </a></strong></p>
                <?php } ?>
            </div>
            <!-- Jogos Abertos da Liga -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg p-4 mt-6">
                <h2 class="text-2xl font-bold mb-2">Jogos Abertos</h2>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                    <tr>
                        <th>Data e Hora</th>
                        <th>Local</th>
                        <th>Jogadores inscritos</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Aqui é onde os jogos abertos são listados.
                         Cada jogo aberto será uma nova linha na tabela. -->
                    <?php foreach ($openLeagueGames as $game) { ?>
                        <tr class="text-center">
                            <td><?= htmlspecialchars($game['data_hora']) ?></td>
                            <td><?= htmlspecialchars($game['local']) ?></td>
                            <td><?= count(GameController::getPlayersInGame($game['id'])) ?>/4</td>
                            <td><a href="/game?id=<?= htmlspecialchars($game['id']) ?>">Ver Jogo</a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <!-- Jogos Em Andamento da Liga -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg p-4 mt-6">
                <h2 class="text-2xl font-bold mb-2">Jogos A decorrer</h2>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                    <tr>
                        <th>Data e Hora</th>
                        <th>Local</th>
                        <th>Jogadores inscritos</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Aqui é onde os jogos abertos são listados.
                         Cada jogo aberto será uma nova linha na tabela. -->
                    <?php foreach ($ongoingLeagueGames as $game) { ?>
                        <tr class="text-center">
                            <td><?= htmlspecialchars($game['data_hora']) ?></td>
                            <td><?= htmlspecialchars($game['local']) ?></td>
                            <td><?= count(GameController::getPlayersInGame($game['id'])) ?>/4</td>
                            <td><a href="/game?id=<?= htmlspecialchars($game['id']) ?>">Ver Jogo</a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>



