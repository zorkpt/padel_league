<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php SessionController::start(); ?>
<?php $header = 'Ligas Públicas' ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>

<?php extract($openLeagues); ?>

<main class="flex-grow">
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="col-span-4">
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
                <?php foreach ($openLeagues as $league) : ?>
                    <tr class="text-center">
                        <td class="px-6 py-4 items-center"><a class="text-blue-500 hover:underline"
                                                              href="/league?id=<?= $league['id'] ?>"><?= htmlspecialchars($league['nome']) ?>
                        </td>
                        <td class="px-6 py-4 items-center"><?= htmlspecialchars($league['descricao']) ?></td>
                        <td class="px-6 py-4 items-center  md:table-cell hidden"><?= htmlspecialchars((new DateTime($league['data_criacao']))->format("d/m/Y")) ?></td>
                        <td class="px-6 py-4 items-center  md:table-cell hidden"><?= htmlspecialchars($league['membros_ativos']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>