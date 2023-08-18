<div class="bg-white shadow-md rounded-lg overflow-hidden p-4 grid-cols-1 md:grid-cols-4 gap-4 md:col-span-2">
    <h2 class="text-2xl font-bold mb-2">Membros</h2>
    <div class="overflow-y-auto max-h-[300px]">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Nome
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Entrou
                </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($leagueMembers as $member) { ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <img class="h-8 w-8 rounded-full mr-2"
                                 src="<?= htmlspecialchars($member['avatar']) ?>"
                                 alt="<?= htmlspecialchars($member['nome_utilizador']) ?>'s avatar">
                            <a class="text-blue-500 underline hover:text-blue-600"
                               href="/profile?id=<?= $member['id'] ?>"
                               class="ml-4 text-sm text-gray-500"><?= htmlspecialchars($member['nome_utilizador']) ?></a>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?= htmlspecialchars((new DateTime($member['data_admissao']))->format("d/m/Y")) ?>
                    </td>

                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>