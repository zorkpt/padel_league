</div>
<footer class="bg-white p-6 text-center">
    <p class="text-gray-800">&copy; 2023 Liga-Padel. Todos os direitos reservados.</p>
</footer>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let notificationButton = document.querySelector("#notification-button");
        let notificationDropdown = document.querySelector("#notification-dropdown");
        let userMenuButton = document.querySelector("#user-menu-button");
        let userMenu = document.querySelector("#user-menu");

        notificationButton.addEventListener('click', function () {
            let displayState = notificationDropdown.style.display;
            notificationDropdown.style.display = displayState === "none" ? "block" : "none";
        });

        userMenuButton.addEventListener('click', function () {
            let displayState = userMenu.style.display;
            userMenu.style.display = displayState === "none" ? "block" : "none";
        });
    });

    document.addEventListener('click', function (event) {
        let isClickInsideNotification = document.getElementById('notification-dropdown').contains(event.target);
        let isNotificationButton = document.getElementById('notification-button').contains(event.target);

        let isClickInsideUserMenu = document.getElementById('user-menu').contains(event.target);
        let isUserMenuButton = document.getElementById('user-menu-button').contains(event.target);

        if (!isClickInsideNotification && !isNotificationButton) {
            let dropdown = document.getElementById('notification-dropdown');
            dropdown.style.display = 'none';
        }

        if (!isClickInsideUserMenu && !isUserMenuButton) {
            let userMenu = document.getElementById('user-menu');
            userMenu.style.display = 'none';
        }


    });

    function setScore(inputId, score) {
        document.getElementById(inputId).value = score;
    }

    function updateScore(scoreId, value, buttonClass) {
        // Atualiza o valor da pontuação
        document.getElementById(scoreId).value = value;

        // Obtem todos os botões da equipa
        var buttons = document.getElementsByClassName(buttonClass);

        // Percorre todos os botões da equipa
        for (var i = 0; i < buttons.length; i++) {
            // Se o botão é o que foi clicado, adiciona a classe 'bg-indigo-600' para o marcar
            // Caso contrário, remove a classe 'bg-indigo-600' para desmarcar os outros botões
            if (buttons[i].innerHTML == value) {
                buttons[i].classList.add('bg-indigo-600');
            } else {
                buttons[i].classList.remove('bg-indigo-600');
            }
        }
    }


</script>

</body>
</html>