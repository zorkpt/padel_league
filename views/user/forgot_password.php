<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Recuperar Senha'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>
<?php session_start() ?>
<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <form method="post" enctype="multipart/form-data">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">


                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Endereço de
                                E-Mail</label>
                            <div class="mt-2">
                                <input id="email" name="email" type="email" autocomplete="email"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <?php if ($errorMessage = SessionController::getFlash('resetPassword')): ?>
                                <div class="text-red-500 mt-2 text-sm">
                                    <?php echo $errorMessage; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="sm:col-span-4">

                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <button type="submit"
                                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    Submeter
                                </button>
                            </div>
        </form>

    </div>


</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>



