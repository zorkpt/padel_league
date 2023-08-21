

<div class="overflow-y-auto max-h-[300px]">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
        <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Username
            </th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Data Pedido
            </th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Mensagem
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Ações
            </th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        <?php foreach ($joinRequests as $request) { ?>
            <tr>
                <td class="px-4 py-4 text-sm text-blue-500"><a href="/profile?id=<?= $request['id_utilizador']?>"><?= htmlspecialchars($request['nome_utilizador']) ?></a></td>
                <td class="px-4 py-4 text-sm text-gray-500"><?= htmlspecialchars((new DateTime($request['data_pedido']))->format("d/m/y H:i")) ?></td>
                <td class="px-4 py-4 break-words text-sm text-gray-500"><?= htmlspecialchars($request['mensagem']) ?></td>

                <td class="px-6 py-4 flex space-x-2">
                    <form method="post" action="/league/request/accept">
                        <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                        <input type="hidden" name="league_id" value="<?= $request['id_liga'] ?>">
                        <input type="hidden" name="user_id" value="<?= $request['id_utilizador'] ?>">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-xs font-medium text-white uppercase transition bg-green-600 rounded shadow ripple hover:shadow-lg hover:bg-green-700 focus:outline-none min-w-[60px] min-h-[30px]">
                            Aceitar
                        </button>
                    </form>
                    <form method="post" action="/league/request/reject">
                        <input type="hidden" name="league_id" value="<?= $request['id_liga'] ?>">
                        <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-xs font-medium text-white uppercase transition bg-red-600 rounded shadow ripple hover:shadow-lg hover:bg-red-700 focus:outline-none min-w-[60px] min-h-[30px]">
                            Rejeitar
                        </button>
                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

