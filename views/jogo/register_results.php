<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Registo'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>
<?php SessionController::start(); ?>
<div class="mb-auto">
    <main class="flex-grow">
        <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">



                <div class="w-full max-w-sm mx-auto p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8">
                    <h1 class="text-xl">Registar resultados dos Sets</h1>
                    <?php
                    $sets = GameController::getSets($game['id']);
                    if (!empty($sets)) {
                        ?>
                        <table class="mx-auto w-1/2 border-collapse">
                            <thead>
                            <tr>
                                <th class="border px-4 py-2">Set</th>
                                <th class="border px-4 py-2">E1</th>
                                <th class="border px-4 py-2">E2</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($sets as $set) { ?>
                                <tr>
                                    <td class='border px-4 py-2 text-center'><?= $set['sequence_number'] ?></td>
                                    <td class='border px-4 py-2 text-center'><?= $set['team1_score'] ?></td>
                                    <td class='border px-4 py-2 text-center'><?= $set['team2_score'] ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <?php
                    }
                    ?>
                    <?php if ($errorMessage = SessionController::getFlash('error')): ?>
                        <div class="text-red-500 mt-2 text-sm">
                            <?php echo $errorMessage; ?>
                        </div>
                    <?php endif; ?>


                    <form action="/game/submit_results?id=<?php echo $game['id']; ?>" method="post">
                        <input type="hidden" name="game_id" value="<?= $game['id'] ?>">

                        <div class="mt-4">
                            <label for="team1_score" class="block">Equipa 1:</label>
                            <div class="mt-1 flex space-x-2">
                                <?php
                                for ($i = 1; $i <= 7; $i++) {
                                    echo "<button type='button' class='team1-btn px-3 py-1 border border-gray-300 rounded shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50' onclick='updateScore(\"team1_score\", $i, \"team1-btn\")'>$i</button>";
                                }
                                ?>
                            </div>
                            <input type="hidden" id="team1_score" name="team1_score">
                        </div>

                        <div class="mt-4">
                            <label for="team2_score" class="block">Equipa 2:</label>
                            <div class="mt-1 flex space-x-2">
                                <?php
                                for ($i = 1; $i <= 7; $i++) {
                                    echo "<button type='button' class='team2-btn px-3 py-1 border border-gray-300 rounded shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50' onclick='updateScore(\"team2_score\", $i, \"team2-btn\")'>$i</button>";
                                }
                                ?>
                            </div>
                            <input type="hidden" id="team2_score" name="team2_score">
                        </div>

                        <button type="submit" class="mt-4 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Submeter
                        </button>
                    </form>
                </div>




        </div>

    </main>

    <?php require BASE_PATH . "/views/partials/footer.php"; ?>



