<?php require "../views/partials/head.php"; ?>
<?php require "../views/partials/nav.php"; ?>
<?php $header = 'Erro'; ?>
<?php require "../views/partials/banner.php"; ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">

        <?php if (isset($errors)): ?>
            <div class="text-red-500 mt-2 text-sm">
                <?php echo $errors; ?>
            </div>
        <?php endif; ?>


    </div>
</main>

<?php require "../views/partials/footer.php"; ?>



