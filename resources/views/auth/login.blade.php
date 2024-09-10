<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - NuevaEspuma</title>
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet"> <!-- Fuente Google -->
    <style>
        body {
            background-color: #222; /* Fondo oscuro */
            color: #fff; /* Texto blanco */
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            width: 100%;
            max-width: 350px;
            padding: 40px;
            background-color: #333; /* Fondo del formulario */
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .login-title {
            color: #c0392b; /* Color del título */
            font-weight: 700;
            margin-bottom: 25px;
            font-size: 1.5rem;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-control {
            background-color: transparent;
            border: none;
            border-bottom: 1px solid #fff;
            border-radius: 0;
            color: #fff;
            padding: 10px;
        }
        .form-control::placeholder {
            color: #aaa;
        }
        .form-control:focus {
            background-color: transparent;
            box-shadow: none;
            border-color: #c0392b;
        }
        .btn-login {
            background-color: #c0392b; /* Color del botón */
            color: white;
            border-radius: 30px;
            font-weight: 500;
            padding: 10px 20px;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            width: 100%;
        }
        .btn-login:hover {
            background-color: #a93226;
        }
        .forgot-password {
            text-align: center;
            display: block;
            margin-top: 15px;
            font-size: 0.9rem;
            color: #c0392b;
        }
        .forgot-password:hover {
            color: #a93226;
            text-decoration: underline;
        }
        .form-check-label {
            margin-left: 5px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 class="login-title">Iniciar Sesión</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email" class="sr-only">Correo Electrónico</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="Correo Electrónico" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password" class="sr-only">Contraseña</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Contraseña" required>
            </div>

            <div class="form-group form-check d-flex align-items-center justify-content-start">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Recordarme</label>
            </div>

            <button type="submit" class="btn btn-login">Ingresar</button>

            @if (Route::has('password.request'))
                <a class="forgot-password" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </form>
    </div>
</body>
</html>