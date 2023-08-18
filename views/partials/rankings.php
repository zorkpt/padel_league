<!--Ranking-->
<div class="bg-white shadow-md rounded-lg overflow-hidden p-4 md:col-span-2">
    <h2 class="text-2xl font-bold mb-2">Classificação</h2>
    <div class="overflow-y-auto max-h-[300px]">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
            <tr class="bg-gray-50">
                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase"></th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jogos</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vitorias
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">WR</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($ranking as $row): ?>
                <tr>
                    <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-500">
                        #<?= $row['rank'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div class="flex items-center">
                            <img class="w-8 h-8 rounded-full mr-2" src="<?= $row['avatar'] ?>"
                                 alt="<?= $row['nome_utilizador'] ?>'s avatar">
                            <a class="text-blue-500 underline hover:text-blue-600"
                               href="/profile?id=<?= $row['id_utilizador'] ?>"><?= $row['nome_utilizador'] ?></a>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['jogos_jogados'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['vitorias'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= number_format($row['win_rate'] ?? 0, 2) ?> %
                    </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>