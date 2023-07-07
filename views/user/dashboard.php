<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php SessionController::start(); ?>
<?php $header = 'Dashboard'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>
<?php
extract($leagues);
?>

<main class="flex-grow">
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <?php if ($validMessage = SessionController::getFlash('success')): ?>
            <div class="text-green-500 mt-2 text-sm">
                <?php echo $validMessage; ?>
            </div>
        <?php endif; ?>
        <table class="min-w-full divide-y shadow-md divide-gray-200">
            <thead class="bg-gray-50">
            <tr class="text-center">
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Nome da Liga</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider  md:table-cell hidden">Data de Criação</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider  md:table-cell hidden">Membros Ativos</th>
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
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a href="/leagues/create"
               class="mx-auto unsubscribe-button rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600">
                Criar nova Liga</a>
        </div>
    </div>

</main>


<?php require BASE_PATH . "/views/partials/footer.php"; ?>



