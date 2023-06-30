</div>
<footer class="bg-white p-6 text-center">
    <p class="text-gray-800">&copy; 2023 Liga-Padel. Todos os direitos reservados.</p>
</footer>
<script>
    // Obtém a referência do botão do menu do usuário
    var userMenuButton = document.getElementById('user-menu-button');

    // Obtém a referência do menu do usuário
    var userMenu = document.getElementById('user-menu');

    // Adiciona um ouvinte de eventos de clique ao botão do menu do usuário
    userMenuButton.addEventListener('click', function() {
        // Quando o botão do menu do usuário é clicado, mostra ou oculta o menu do usuário
        userMenu.classList.toggle('hidden');
    });
</script>

</body>
</html>