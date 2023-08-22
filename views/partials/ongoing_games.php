<div class="bg-white shadow-md rounded-lg overflow-hidden p-4 grid-cols-1 md:grid-cols-4 gap-4 md:col-span-2">
    <h2 class="text-2xl font-bold mb-2">Jogos a Decorrer</h2>
    <div class="overflow-y-auto max-h-[300px]">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Data
                </th>
                <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Hora
                </th>
                <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Local
                </th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Ações
                </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($ongoingLeagueGames as $game) { ?>
                <tr>
                    <td class="px-2 py-4 whitespace-nowrap text-center text-sm text-gray-500"><?= htmlspecialchars((new DateTime($game['data_hora']))->format("d/m")) ?></td>
                    <td class="px-2 py-4 whitespace-nowrap text-center text-sm text-gray-500"><?= htmlspecialchars((new DateTime($game['data_hora']))->format("H:i")) ?></td>
                    <td class="px-6 py-4 text-center items-center"><?= htmlspecialchars($game['local']) ?></td>

                    <td class="px-6 py-4 items-center">
                        <div class="text-center justify-center">
                            <a class="inline-flex items-center justify-center px-4 py-2 text-xs font-medium text-white uppercase transition bg-indigo-600 rounded shadow ripple hover:shadow-lg hover:bg-indigo-800 focus:outline-none min-w-[60px] min-h-[30px]"
                               href="/game?id=<?= htmlspecialchars($game['id']) ?>">Abrir</a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>