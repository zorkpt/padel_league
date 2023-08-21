<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Perfil'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>
<div class="mb-auto">

<main class="flex-grow">
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <div class="w-full max-w-sm mx-auto p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8">
            <!-- Perfil do utilizador -->
            <section>
                <h2 class="text-xl font-semibold mb-2">Perfil do utilizador</h2>
                <form method="post" action="/user/updateEmail">
                    <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Nome de
                        Utilizador:</label><br>
                    <input class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                           type="text" id="username" name="username" value="<?= $_SESSION['user']['nome_utilizador'] ?>"
                           readonly><br>
                    <label class="block text-sm font-medium leading-6 text-gray-900" for="email">Endereço de E-Mail:</label><br>
                    <input class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                           type="email" id="email" name="email" value="<?= $_SESSION['user']['email'] ?>"><br>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <?php if ($message = SessionController::getFlash('email')): ?>
                            <div class="text-red-500 mt-2 text-sm">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($message = SessionController::getFlash('success_mail_message')): ?>
                            <div class="text-green-500 mt-2 text-sm">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>
                        <button type="submit"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Atualizar E-Mail
                        </button>
                    </div>
                </form>
            </section>

            <hr class="border-gray-300 my-5">

            <!-- Alterar Avatar -->
            <section>
                <h3 class="text-xl font-semibold mb-2">Alterar Avatar</h3>
                <form method="post" action="/user/updateAvatar" enctype="multipart/form-data" class="space-y-2">
                    <div class="relative">
                        <!-- Avatar Atual como Ícone -->
                        <img src="<?= $user['avatar'] ?>" alt="Avatar Atual" class="absolute left-2 top-1/2 transform -translate-y-1/2 w-10 h-10 rounded-full shadow-sm">

                        <!-- Input File -->
                        <input class="pl-14 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="file" id="avatar" name="avatar" placeholder="Escolha um novo avatar...">
                    </div>

                    <!-- Mensagens -->
                    <?php if ($message = SessionController::getFlash('avatar')): ?>
                        <div class="text-red-500 mt-2 text-sm">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($message = SessionController::getFlash('success_avatar_message')): ?>
                        <div class="text-green-500 mt-2 text-sm">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Botão -->
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Atualizar Avatar
                        </button>
                    </div>
                </form>
            </section>

            <hr class="border-gray-300 my-5">

            <!-- Alterar senha -->
            <section>
                <h3 class="text-xl font-semibold mb-2">Alterar senha</h3>
                <?php if ($message = SessionController::getFlash('success_password_message')): ?>
                    <div class="text-green-500 mt-2 text-sm">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="/user/updatePassword">
                    <label class="block text-sm font-medium leading-6 text-gray-900" for="old_password">Senha atual:</label><br>
                    <input class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="password" id="old_password" name="old_password"><br>
                    <label class="block text-sm font-medium leading-6 text-gray-900" for="new_password">Nova senha:</label><br>
                    <input class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="password" id="new_password" name="new_password"><br>
                    <label class="block text-sm font-medium leading-6 text-gray-900" for="confirm_new_password">Confirmar Nova senha:</label><br>
                    <input class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="password" id="confirm_new_password" name="confirm_new_password"><br>

                    <?php if ($message = SessionController::getFlash('password')): ?>
                        <div class="text-red-500 mt-2 text-sm">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($message = SessionController::getFlash('success_mail_message')): ?>
                        <div class="text-green-500 mt-2 text-sm">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <button type="submit"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Alterar Senha
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>



