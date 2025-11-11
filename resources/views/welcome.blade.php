<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Docentes y Horarios</title>
    <style>
        /* Paleta Deep Teal Sea */
        :root {
            --deep-teal: #012E40;
            --dark-teal: #024959;
            --medium-teal: #026773;
            --light-teal: #3CA6A6;
            --cream: #F2E3D5;
        }

        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--deep-teal) 0%, var(--dark-teal) 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: var(--deep-teal);
        }

        .container {
            background-color: var(--cream);
            padding: 50px 30px;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(1, 46, 64, 0.2);
            max-width: 500px;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(to right, var(--light-teal), var(--medium-teal));
        }

        .logo {
            margin-bottom: 25px;
        }

        .logo-icon {
            font-size: 48px;
            color: var(--medium-teal);
            margin-bottom: 10px;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: var(--deep-teal);
            font-weight: 600;
            line-height: 1.3;
        }

        p {
            margin-bottom: 35px;
            color: var(--dark-teal);
            line-height: 1.6;
            font-size: 17px;
        }

        .user-welcome {
            background-color: rgba(60, 166, 166, 0.1);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 4px solid var(--light-teal);
        }

        .user-welcome strong {
            color: var(--dark-teal);
        }

        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 30px;
        }

        a, button {
            display: inline-block;
            padding: 14px 25px;
            font-size: 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 600;
            width: 100%;
        }

        .btn-primary {
            background-color: var(--medium-teal);
            color: white;
            box-shadow: 0 4px 12px rgba(2, 103, 115, 0.3);
        }

        .btn-primary:hover {
            background-color: var(--dark-teal);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(2, 103, 115, 0.4);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--medium-teal);
            border: 2px solid var(--medium-teal);
        }

        .btn-secondary:hover {
            background-color: rgba(2, 103, 115, 0.1);
            transform: translateY(-2px);
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: var(--dark-teal);
            opacity: 0.8;
        }

        /* Responsive para móviles */
        @media (max-width: 500px) {
            .container {
                padding: 40px 20px;
            }

            h1 {
                font-size: 24px;
            }

            p {
                font-size: 16px;
            }

            a, button {
                font-size: 15px;
                padding: 12px 20px;
            }
        }

        @media (max-width: 380px) {
            .container {
                padding: 30px 15px;
            }

            h1 {
                font-size: 22px;
            }

            .logo-icon {
                font-size: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <div class="logo-icon">📚</div>
        </div>
        
        <h1>Sistema de Gestión de Docentes y Horarios</h1>

        @auth
            <div class="user-welcome">
                <p>Bienvenido, <strong>{{ auth()->user()->name}}</strong></p>
            </div>
            
            <div class="btn-container">
                <a href="{{ url('/dashboard') }}" class="btn-primary">Ir al panel de control</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-secondary">Cerrar sesión</button>
                </form>
            </div>
        @else
            <p>Bienvenido, por favor inicia sesion para poder acceder.</p>
            <div class="btn-container">
                <a href="{{ route('login') }}" class="btn-primary">Iniciar sesión</a>
            </div>
            
            <div class="footer">
                
            </div>
        @endauth
    </div>
</body>
</html>