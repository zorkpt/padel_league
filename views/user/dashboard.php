<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php Session::start(); ?>
<?php $header = 'Dashboard'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>
<?php
extract($leagues);
?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <table class="table-auto">
            <thead>
            <tr>
                <th class="px-4 py-2">Nome da Liga</th>
                <th class="px-4 py-2">Descrição</th>
                <th class="px-4 py-2">Data de Criação</th>
                <th class="px-4 py-2">Membros Ativos</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($leagues as $league) : ?>
                <tr>
                    <td class="border px-4 py-2"><a class="text-blue-500 hover:underline"
                                                    href="league?id=<?= $league['id'] ?>"><?= htmlspecialchars($league['nome']) ?>
                    </td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($league['descricao']) ?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($league['data_criacao']) ?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($league['membros_ativos']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a href="/leagues/create"
               class="unsubscribe-button rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600">
                Criar nova Liga</a>
        </div>
    </div>
</main


<?php require BASE_PATH . "/views/partials/footer.php"; ?>



