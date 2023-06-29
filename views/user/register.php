<?php require BASE_PATH . "/views/partials/head.php"; ?>
<?php require BASE_PATH . "/views/partials/nav.php"; ?>
<?php $header = 'Registo'; ?>
<?php require BASE_PATH . "/views/partials/banner.php"; ?>
<?php session_start() ?>
<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <form method="post" enctype="multipart/form-data">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Perfil</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Estás perto de te juntar a nós!</p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label for="username"
                                   class="block text-sm font-medium leading-6 text-gray-900">Nome de Utilizador</label>
                            <div class="mt-2">
                                <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                    <span class="flex select-none items-center pl-3 text-gray-500 sm:text-sm">zork.pt/</span>
                                    <input type="text" name="username" id="username" autocomplete="username"
                                           class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                           placeholder="nomedeutilizador">
                                </div>
                            </div>
                            <?php if (isset($_SESSION['errors']['username'])): ?>
                                <div class="text-red-500 mt-2 text-sm">
                                    <?php echo $_SESSION['errors']['username']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="sm:col-span-4">
                            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Endereço de
                                E-Mail</label>
                            <div class="mt-2">
                                <input id="email" name="email" type="email" autocomplete="email"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <?php if (isset($_SESSION['errors']['email'])): ?>
                                <div class="text-red-500 mt-2 text-sm">
                                    <?php echo $_SESSION['errors']['email']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="sm:col-span-4">
                            <label for="password"
                                   class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                            <div class="mt-2">
                                <input id="password" name="password" type="password" autocomplete="current-password"
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <?php if (isset($_SESSION['errors']['password'])): ?>
                                <div class="text-red-500 mt-2 text-sm">
                                    <?php echo $_SESSION['errors']['password']; ?>
                                </div>
                            <?php endif; ?>


<!--                             avatar-->
                            <div class="sm:col-span-4">
                                <label for="avatar" class="block text-sm font-medium leading-6 text-gray-900">Avatar</label>
                                <div class="mt-2 flex items-center gap-x-3">
                                    <svg class="h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd" />
                                    </svg>
                                    <input id="avatar" name="avatar" type="file"
                                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                                <?php if (isset($_SESSION['errors']['avatar'])): ?>
                                    <div class="text-red-500 mt-2 text-sm">
                                        <?php echo $_SESSION['errors']['avatar']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>


                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel
                                </button>
                                <button type="submit"
                                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    Save
                                </button>
                            </div>
        </form>

    </div>



</main>

<?php require BASE_PATH . "/views/partials/footer.php"; ?>



