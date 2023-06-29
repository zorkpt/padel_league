


<?php require  BASE_PATH . "/views/partials/head.php"; ?>
<?php require  BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Perfil'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <div class="container mx-auto my-10 p-6 md:px-12">

            <div class="w-full md:w-1/2 lg:w-1/3 flex flex-col items-center mx-auto">

                <!-- Avatar -->

                <div class="w-32 h-32 mb-4 rounded-full bg-gray-400"><img alt="avatar" class="rounded-full" src="<?= $_SESSION['user']['avatar']; ?>"> </div>

                <!-- Nome do utilizador -->
<!--                --><?php //dd($user_name); ?>
                <h2 class="text-2xl font-semibold mb-2 mt-4"><?php echo $user_name['nome_utilizador']; ?></h2>

                <!-- W/L Ratio -->
                <div class="text-gray-600 mb-10">
                    <span>Jogos: <?= $score['jogos_jogados'];  ?> </span>
                    <span>Vitorias: <?= $score['jogos_ganhos']; ?></span>
                    <span>Win Rate: <?php echo $win_loss_ratio; ?></span>
                </div>

                <!-- Botão de Enviar Mensagem -->
                <button class="mb-4 bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Enviar Mensagem
                </button>

                <!-- Ligas -->
                <h3 class="text-lg font-semibold mb-2">Ligas</h3>
<!---->

                <table class="table-auto">
                    <thead>
                    <tr>
                        <th class="px-4 py-2">Nome da Liga</th>
                        <th class="px-4 py-2">Descrição</th>

                        <th class="px-4 py-2">Membros Ativos</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($leagues as $league) : ?>
                        <tr>
                            <td class="border px-4 py-2"><a class="text-blue-500 hover:underline"
                                                            href="league?id=<?= $league['id'] ?>"><?= htmlspecialchars($league['nome']) ?></a>
                            </td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($league['descricao']) ?></td>

                            <td class="border px-4 py-2"><?= htmlspecialchars($league['membros_ativos']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>


            </div>

        </div>
    </div>
</main>

<?php require  BASE_PATH . "/views/partials/footer.php"; ?>



