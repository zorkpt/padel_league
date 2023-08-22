<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Jogo'; ?>

<?php require BASE_PATH . "/views/partials/banner.php"; ?>
<?php extract($players); ?>
<?php extract($playerIds); ?>
<?php $currentUserId = $_SESSION['user']['id']; ?>
<?php $creatorID = $game['criador'] ?>
<?php $creatorName = UserController::getUserData($creatorID)['nome_utilizador']; ?>


<div class="mb-auto">
    <main>
        <div class="mx-auto h-300px max-w-7xl py-6 sm:px-6 lg:px-8">
            <a href="/league?id=<?= $game['id_liga'] ?>"
               class="mb-4 inline-block px-6 py-2 text-xs font-medium leading-6 text-center text-white uppercase transition bg-indigo-600 rounded shadow ripple hover:shadow-lg hover:bg-indigo-800 focus:outline-none">
                Voltar
            </a>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!--info-->
                <div class="bg-white shadow-md rounded-lg p-6 md:col-span-2">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Local: <?php echo $game['local']; ?>
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Data: <?php echo date("d/m/Y H:i", strtotime($game['data_hora'])); ?>
                        </p>
                        <p class="mt-2 text-sm text-gray-500">
                            Status: <?php if ($game['status'] == 1) {
                                echo "Aberto";
                            } else if ($game['status'] == 0) {
                                echo "Terminado";
                            } else echo "A Decorrer...";

                            ?>
                        </p>
                        <p class="mt-2 text-sm text-gray-500">Criador do jogo: <?= $creatorName ?></p>
                    </div>
                    <?php if ($errors = SessionController::getFlash('error')): ?>
                        <div class="text-red-500 mt-2 text-sm">
                            <?= $errors ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($success = SessionController::getFlash('success')): ?>
                        <div class="text-green-500 mt-2 text-sm">
                            <?= $success ?>
                        </div>
                    <?php endif; ?>
                </div>
                <!--end info-->


                <!--                start team games and registered players-->
                <div class="bg-white shadow-md rounded-lg overflow-hidden p-4 md:col-span-2">

                    <?php if ($game['status'] == GAME_LOCKED || $game['status'] == GAME_FINISHED): ?>
                        <!-- Flex container -->
                        <div class="flex flex-col md:flex-row justify-center items-center">
                            <!-- Team 1 -->
                            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 ml-2 flex flex-col">

                                <h2 class="mb-4 text-xl font-bold text-center text-gray-700">EQUIPA 1:</h2>
                                <ul class="mb-4 text-gray-700 text-center">
                                    <?php foreach ($players as $player): ?>
                                        <?php if ($player['equipa'] == 1): ?>
                                            <li class="flex items-center justify-center">
                                                <img src="<?= $player['avatar'] ?>" alt="Avatar"
                                                     class="w-8 h-8 rounded-full mr-2">
                                                <?= $player['nome_utilizador'] ?>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <!-- Score -->
                            <div class="mx-4 bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col">
                                <h2 class="mb-1 text-xl font-bold text-center text-gray-700">Score:</h2>
                                <div class="text-center mb-1 text-gray-700"><?= $game['team1_score'] ?>
                                    - <?= $game['team2_score'] ?></div>
                                <div class="text-center text-gray-700">
                                    <table class="mx-auto w-1/4 border-collapse">
                                        <tr>
                                            <th class="border px-2 py-1"></th>
                                            <th class="border px-2 py-1 bg-gray-200">S1</th>
                                            <th class="border px-2 py-1 bg-gray-200">S2</th>
                                            <th class="border px-2 py-1 bg-gray-200">S3</th>
                                        </tr>
                                        <tr>
                                            <td class="border px-2 py-1 text-center">E1</td>
                                            <?php
                                            $sets = GameController::getSets($game['id']);
                                            for ($i = 0; $i < 3; $i++) {
                                                if (isset($sets[$i])) {
                                                    echo "<td class='border px-2 py-1 text-center'>" . $sets[$i]['team1_score'] . "</td>";
                                                } else {
                                                    echo "<td class='border px-2 py-1 text-center'>-</td>";
                                                }
                                            }
                                            ?>
                                        </tr>
                                        <tr>
                                            <td class="border px-2 py-1 text-center">E2</td>
                                            <?php
                                            for ($i = 0; $i < 3; $i++) {
                                                if (isset($sets[$i])) {
                                                    echo "<td class='border px-2 py-1 text-center'>" . $sets[$i]['team2_score'] . "</td>";
                                                } else {
                                                    echo "<td class='border px-2 py-1 text-center'>-</td>";
                                                }
                                            }
                                            ?>
                                        </tr>
                                    </table>


                                </div>
                            </div>

                            <!-- Team 2 -->
                            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col">
                                <h2 class="mb-4 mr-3 text-xl font-bold text-center text-gray-700">EQUIPA 2:</h2>
                                <ul class="mb-4 text-gray-700 text-center">
                                    <?php foreach ($players as $player): ?>
                                        <?php if ($player['equipa'] == 2): ?>
                                            <li class="flex items-center justify-center">
                                                <img src="<?= $player['avatar'] ?>" alt="Avatar"
                                                     class="w-8 h-8 rounded-full mr-2">
                                                <?= $player['nome_utilizador'] ?>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($game['status'] == GAME_OPEN): ?>
                        <div class="mt-4 flex justify-center items-center">
                            <!-- Registered Players -->
                            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col">
                                <h2 class="mb-4 text-xl font-bold text-center text-gray-700">Jogadores
                                    inscritos:</h2>
                                <ul class="mb-4 text-gray-700 text-center">
                                    <?php foreach ($players as $player): ?>
                                        <li class="flex items-center justify-center">
                                            <img src="<?= $player['avatar'] ?>" alt="Avatar"
                                                 class="w-8 h-8 rounded-full mr-2">
                                            <?= $player['nome_utilizador'] ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!--            end team games and registered players-->

                <?php if ($isVisitor): ?>
                    <div class="bg-white shadow-md rounded-lg p-6 md:col-span-2">
                        <div class="mt-6 flex flex-col items-start gap-y-4">
                            <p class="mt-2 text-sm text-gray-500"> A ver o jogo como visitante </p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!$isVisitor): ?>
                    <!-- start buttons-->
                    <div class="bg-white shadow-md rounded-lg p-6 md:col-span-2">
                        <div class="mt-6 flex flex-col items-start gap-y-4">
                            <!-- Altere para flex-col e items-start, e mude gap-x-6 para gap-y-4 -->
                            <?php if ($game['status'] == GAME_OPEN): ?>
                                <?php if (!in_array($currentUserId, $playerIds)): ?>
                                    <?php if (count($playerIds) < MAX_PLAYERS): ?>
                                        <a class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                           href="game/subscribe?id=<?php echo $game['id']; ?>">Inscrever-se</a>
                                    <?php else: ?>
                                        <div>Jogo cheio</div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a href="/game/unsubscribe?id=<?php echo $game['id']; ?>"
                                       class="unsubscribe-button rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600">Cancelar
                                        inscrição</a>
                                <?php endif; ?>
                            <?php endif; ?>


                            <!-- Verify if game can be locked  -->
                            <?php if ($game['status'] == GAME_OPEN): ?>
                                <?php if (count($playerIds) == MAX_PLAYERS): ?>
                                    <a href="/game/lock?game_id=<?= $game['id'] ?>"
                                       class="unsubscribe-button rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600">Trancar
                                        Jogo</a>
                                <?php else: ?>
                                    <div class="lock-button cursor-not-allowed opacity-50">Trancar Jogo</div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if (!isset($_SESSION['adjustTeams']) && $currentUserId == $creatorID && $game['status'] == GAME_LOCKED && !$resultsExist): ?>
                                <a href="/game/change_teams?id=<?php echo $game['id']; ?>"
                                   class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Alterar
                                    Equipas</a>
                            <?php endif; ?>

                            <?php if ($game['status'] == GAME_LOCKED && $game['fim_jogo'] == 0): ?>
                                <a href="/game/register_results?id=<?php echo $game['id']; ?>"
                                   class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Registrar
                                    Resultados</a>
                            <?php else: ?>
                                <div class="cursor-not-allowed opacity-50">Registrar Resultados</div>
                            <?php endif; ?>

                            <?php if ($game['status'] == GAME_LOCKED && $resultsExist): ?>
                                <form action="/game/finish" method="POST">
                                    <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
                                    <input type="submit" value="Terminar Jogo"
                                           class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                </form>
                            <?php else: ?>
                                <div class="cursor-not-allowed opacity-50">Terminar Jogo</div>
                            <?php endif; ?>

                            <?php if ($currentUserId == $creatorID && $game['status'] != GAME_FINISHED): ?>
                                <button
                                        id="triggerModal"
                                        class="mb-4 inline-block px-6 py-2 text-xs font-medium leading-6 text-center text-white uppercase transition bg-red-600 rounded shadow ripple hover:shadow-lg hover:bg-red-800 focus:outline-none"
                                >
                                    Cancelar Jogo
                                </button>
                                <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
                            <?php endif; ?>
                        </div>
                    </div>
                    <!--end buttons-->

                    <!-- start change teams-->
                    <div class="bg-white shadow-md rounded-lg overflow-hidden p-4 md:col-span-2 space-y-4">
                        <!-- adicionar space-y-4 -->
                        <div class="overflow-y-auto max-h-[400px]"> <!-- aumentar a altura máxima para 400px -->
                            <?php if (isset($_SESSION['adjustTeams']) && $currentUserId === $creatorID): ?>
                                <?php if ($game['status'] == GAME_LOCKED && !$resultsExist): ?>
                                    <form action="/game/change_teams" method="POST">
                                        <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
                                        <div class="mt-4 flex justify-center items-center">
                                            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col">
                                                <h2 class="mb-4 text-xl font-bold text-center text-gray-700">Alterar
                                                    equipas:</h2>
                                                <ul class="mb-4 text-gray-700 text-center">
                                                    <?php foreach ($players as $player): ?>
                                                        <li class="flex items-center justify-center">
                                                            <img src="<?= $player['avatar'] ?>" alt="Avatar"
                                                                 class="w-8 h-8 rounded-full mr-2">
                                                            <?= $player['nome_utilizador'] ?>
                                                            <select class="ml-5 mb-3" name="team[<?= $player['id'] ?>]">
                                                                <option value="1" <?= $player['equipa'] == 1 ? 'selected' : '' ?>>
                                                                    Equipa 1
                                                                </option>
                                                                <option value="2" <?= $player['equipa'] == 2 ? 'selected' : '' ?>>
                                                                    Equipa 2
                                                                </option>
                                                            </select>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <input type="submit" value="Alterar equipas"
                                               class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 mb-4">
                                        <!-- adicionar mb-4 -->
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endif ?>
            </div>
        </div>


    </main>

    <div id="confirmationModal" class="fixed inset-0 flex items-center justify-center z-50 invisible opacity-0 transition-all">
        <div class="modal-content bg-white p-6 rounded shadow-lg w-1/3">
            <p class="mb-4">Tens a certeza que queres cancelar o jogo?</p>
            <div class="flex justify-end">
                <button id="confirmLeave" class="bg-red-600 text-white px-4 py-2 rounded mr-2 hover:bg-red-700">Sim,
                    cancelar
                </button>
                <button id="cancelLeave" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Não</button>
            </div>
        </div>
    </div>


    <form id="cancelGameForm" method="post" action="/game/cancel">
        <input type="hidden" name="game_id" value="<?= $game['id']; ?>">
        <input type="hidden" name="league_id" value="<?= $game['id_liga'] ?>">
    </form>


    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const modal = document.getElementById('confirmationModal');
            const confirmButton = document.getElementById('confirmLeave');
            const cancelButton = document.getElementById('cancelLeave');
            const leaveLeagueForm = document.getElementById('cancelGameForm');
            const triggerModalButton = document.getElementById('triggerModal');

            triggerModalButton.addEventListener('click', function (e) {
                e.preventDefault();
                modal.classList.remove('invisible');
                modal.style.opacity = 1;
            });

            // Quando o botão confirmar no modal for clicado, envia o formulário.
            confirmButton.addEventListener('click', function () {
                modal.classList.add('invisible');
                leaveLeagueForm.submit();
            });

            // Quando o botão cancelar no modal for clicado, apenas fecha o modal.
            cancelButton.addEventListener('click', function () {
                modal.style.opacity = 0;
                setTimeout(() => {
                    modal.classList.add('invisible');
                }, 300); // A duração da transição
            });
        });


    </script>
    <?php require BASE_PATH . "/views/partials/footer.php"; ?>
