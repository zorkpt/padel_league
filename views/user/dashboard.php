<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php SessionController::start(); ?>
<?php
extract($leagues);
extract($lastGames);
?>
<div class="mb-auto">

<header class="bg-white shadow">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard</h1>

            <div class="flex items-center gap-x-6 mt-4 md:mt-0">
                <a href="/leagues/create"
                   class="unsubscribe-button rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600">
                    Criar nova Liga</a>

                <a href="/league/join"
                   class="unsubscribe-button rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600">
                    Inserir Código Convite</a>
            </div>
        </div>
    </div>
</header>

<main class="flex-grow">
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="col-span-4">
            <?php if ($validMessage = SessionController::getFlash('success')): ?>
                <div class="text-green-500 mt-2 text-sm">
                    <?php echo $validMessage; ?>
                </div>
            <?php endif; ?>
            <h1 class="pl-5 block mb-2 text-sm font-medium text-gray-900">As minhas ligas</h1>
            <table class="min-w-full divide-y shadow-md divide-gray-200">
                <thead class="bg-gray-50">
                <tr class="text-center">
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Nome da Liga</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider  md:table-cell hidden">
                        Data de Criação
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider  md:table-cell hidden">
                        Membros Ativos
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($leagues as $league) : ?>
                    <tr class="text-center">
                        <td class="px-6 py-4 items-center"><a class="text-blue-500 hover:underline"
                                                              href="league?id=<?= $league['id'] ?>"><?= htmlspecialchars($league['nome']) ?>
                        </td>
                        <td class="px-6 py-4 items-center"><?= htmlspecialchars($league['descricao']) ?></td>
                        <td class="px-6 py-4 items-center  md:table-cell hidden"><?= htmlspecialchars((new DateTime($league['data_criacao']))->format("d/m/Y")) ?></td>
                        <td class="px-6 py-4 items-center  md:table-cell hidden"><?= htmlspecialchars($league['membros_ativos']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Ligas Proximas de ti -->
        <div class="bg-white shadow-md rounded-lg p-6 col-span-4 md:col-span-2">
            <h2 class="mb-4 text-lg font-bold">Ligas Proximas de ti</h2>
        </div>

        <!-- O teu último jogo foi há -->
        <div class="bg-white shadow-md rounded-lg p-6 col-span-4 md:col-span-1 text-center">
        <p class="mb-4 text-lg font-bold">O teu último jogo foi há:</p>
            <div class="text-4xl font-bold text-blue-600 my-2"><?php if($daysSinceLastGame==-1){echo "Sem jogos.";} else echo $daysSinceLastGame ?></div>
            <?php if ($daysSinceLastGame == 1): ?>
                <p class="text-gray-500">dia</p>
            <?php elseif($daysSinceLastGame > 1): ?>
                <p class="text-gray-500">dias</p>
            <?php endif; ?>
        </div>

        <!-- Últimos jogos -->
        <div class="bg-white shadow-md rounded-lg p-6 col-span-4 md:col-span-1 overflow-y-auto">
        <h2 class="mb-4 text-lg font-bold">Últimos jogos:</h2>
            <?php foreach ($lastGames as $game): ?>
                <a href="game?id=<?= $game['id'] ?>" class="block mb-4">
                    <div class="p-4 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center">
                            <div class="<?= $game['Resultado'] == 'Vitória' ? 'bg-green-500' : 'bg-red-500' ?> w-4 h-4 rounded-full mr-2"></div>
                            <span class="font-bold text-sm"><?= $game['Resultado'] == 'Vitória' ? 'Vitória' : 'Derrota' ?> </span>
                            <span class="ml-auto text-sm text-gray-600"><?= $game['team1_score'] ?> - <?= $game['team2_score'] ?></span>
                        </div>
                        <p class="mt-2 text-sm text-gray-600">Local: <?= $game['local'] ?></p>
                        <p class="text-sm text-gray-600">
                            Data: <?= (new DateTime($game['data_hora']))->format('d/m/Y H:i') ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

</main>


<?php require BASE_PATH . "/views/partials/footer.php"; ?>



