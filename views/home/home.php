<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liga Padel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<nav class="bg-white p-6">
    <div class="container mx-auto flex items-center justify-between">
        <div>
            <a href="/" class="text-lg font-semibold text-gray-800">Liga Padel</a>
        </div>
        <div class="flex items-center">
            <a href="/login" class="text-gray-800 mx-2">Entrar</a>
            <a href="/register" class="bg-blue-500 text-white px-3 py-2 rounded">Registar</a>
        </div>
    </div>
</nav>

<header class="bg-gray-600 text-white text-center py-16">
    <h1 class="text-4xl font-bold">Bem-vindo à Liga-Padel.pt</h1>
    <p class="text-xl mt-2">A plataforma que te permite profissionalizar os teus jogos amigáveis de Padel.</p>
</header>
<main class="container mx-auto px-6 py-8">
    <h2 class="text-2xl font-bold text-gray-800">Como funciona</h2>
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-xl font-bold">Organiza ligas</h3>
            <p class="mt-2 text-gray-600">Crie e gere ligas, agenda partidas e registra os resultados.</p>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-xl font-bold">Participa em ligas</h3>
            <p class="mt-2 text-gray-600">Inscreve-te em ligas, marca jogos e acompanha a tua progressão.</p>
        </div>
    </div>
</main>
<?php require BASE_PATH . "/views/partials/footer.php"; ?>
</body>
</html>
