<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Menú de Apuestas</title>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <img src="logo.png" alt="Logo del sitio">
            </div>
            <div class="menu">
                <ul class="menu-list">
                    <li><a href="#">Inicio</a></li>
                    <li><a href="#">Apuestas</a></li>
                    <li><a href="#">Promociones</a></li>
                    <li><a href="#">Contacto</a></li>
                </ul>
            </div>
            <div class="auth-buttons">
                <button class="login-btn" onclick="showLogin()">Iniciar Sesión</button>
                <button class="signup-btn" onclick="showSignup()">Crear Cuenta</button>
            </div>
        </nav>
    </header>
    
    <div id="login-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <form>
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Ingresar</button>
            </form>
        </div>
    </div>

    <script>
        function showLogin() {
    document.getElementById('login-modal').style.display = 'block';
}

function closeModal() {
    document.getElementById('login-modal').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('login-modal')) {
        document.getElementById('login-modal').style.display = 'none';
    }
}

    </script>
</body>
</html>
