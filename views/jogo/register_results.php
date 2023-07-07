<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Registo'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>
<?php SessionController::start(); ?>
<div class="mb-auto">
<main class="flex-grow">
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">

        <div class="w-full max-w-sm mx-auto p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8">
        <form action="/game/submit_results?id=<?php echo $game['id']; ?>" method="post">
            <input type="hidden" name="game_id" value="<?= $game['id'] ?>">

            <label for="team1_score">Pontuação da Equipa 1:</label>
            <input type="number" id="team1_score" name="team1_score">

            <label for="team2_score">Pontuação da Equipa 2:</label>
            <input type="number" id="team2_score" name="team2_score">

            <button type="submit" class="mt-2 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Registrar Resultados</button>
        </form>

    </div>

    </div>

</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>



