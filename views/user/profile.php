<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Perfil'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>
<div class="mb-auto">
<main class="flex-grow">

    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <div class="w-full max-w-sm mx-auto p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8">
            <div class="flex flex-col items-center">
                <!-- Avatar -->
                <div class="w-32 h-32 mb-4 rounded-full bg-gray-400">
                    <img alt="avatar" class="object-cover w-full h-full rounded-sm"
                         src="<?= $user_name['avatar']; ?>">
                </div>
                <!-- Nome do utilizador -->
                <h2 class="text-2xl font-semibold mb-2 mt-4"><?= $user_name['nome_utilizador']; ?></h2>
                <!-- W/L Ratio -->
                <div class="text-gray-600 mb-10 space-y-1">
                    <div>Jogos: <?= $score['jogos_jogados']; ?> </div>
                    <div>Vitorias: <?= $score['jogos_ganhos']; ?></div>
                    <div>Win Rate: <?= $win_loss_ratio; ?></div>
                </div>
                <!-- Botão de Enviar Mensagem -->
                <button class="mb-4 bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                    Enviar Mensagem
                </button>
                <!-- Ligas -->
                <h3 class="text-lg font-semibold mb-2">Ligas</h3>
                <table class="w-full table-auto">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Liga
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rank
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Membros
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">

                    <?php foreach ($leagues as $league) : ?>
                        <tr>
                            <td class="px-4 py-2">
                                <a class="text-indigo-600 hover:underline"
                                   href="league?id=<?= $league['id'] ?>"><?= htmlspecialchars($league['nome']) ?></a>
                            </td>
                            <td class="px-4 py-2">
                                <?php if (isset($league['ranking'])): ?>
                                <?= htmlspecialchars($league['ranking']) ?>º
                            </td>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                            <td class="px-4 py-2">

                                <?= htmlspecialchars($league['membros_ativos']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>



