<?php
session_start();
require '../../../PUBLIC/Config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header('Content-Type: application/json');
    $user = $_POST['usuario_correo'];
    $pass = $_POST['usuario_password'];

    $usuarios_query = "SELECT * FROM usuarios WHERE usuario_correo = :correo AND usuario_estado = :estado";
    $stmt = $conn->prepare($usuarios_query);
    $stmt->bindValue(":correo", $user, PDO::PARAM_STR);
    $stmt->bindValue(":estado", "activo", PDO::PARAM_STR);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        if (password_verify($pass, $usuario['usuario_password'])) {
            $_SESSION['usuario_documento'] = $usuario['usuario_documento'];
            $_SESSION['usuario_nombre'] = $usuario['usuario_nombre'];
            $_SESSION['usuario_apellido'] = $usuario['usuario_apellido'];
            $_SESSION['rol_id'] = $usuario['rol_id'];
            $_SESSION['escuela_id'] = $usuario['escuela_id'];
            $_SESSION['usuario_correo'] = $usuario['usuario_correo'];
            $_SESSION['usuario_telefono'] = $usuario['usuario_telefono'];
            $_SESSION['usuario_direccion'] = $usuario['usuario_direccion'];
            $_SESSION['usuario_nacimiento'] = $usuario['usuario_nacimiento'];
            $_SESSION['usuario_imagen_url'] = $usuario['usuario_imagen_url'];

            echo json_encode(["success" => true, "message" => "Inicio de sesión exitoso"]);
        } else {
            echo json_encode(["success" => false, "message" => "Correo electrónico o contraseña incorrectos"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Correo electrónico o contraseña incorrectos"]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Classment Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="IMG/logo_mini.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
    .gradient-circle {
      position: absolute;
      border-radius: 50%;
      filter: blur(50px);
    }

    body {
      margin: 0;
      height: 100vh;
      background-color: #1e3c72;
      overflow: hidden;
    }

    .bubble-container {
      position: absolute;
      width: 100%;
      height: 100%;
      pointer-events: none; /* Para que las burbujas no interfieran con los elementos interactivos */
    }

    .bubble {
      position: absolute;
      bottom: -100px;
      width: 40px;
      height: 40px;
      background: rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      animation: rise 10s infinite ease-in-out;
    }

    @keyframes rise {
      0% { transform: translateY(0); }
      100% { transform: translateY(-1200px); }
    }
  </style>

</head>

<div class="bg-black min-h-screen relative overflow-hidden">
    <!-- Círculos de gradiente -->
    <div class="gradient-circle w-44 h-32 bg-orange-300 top-10 left-10 opacity-40"></div>
    <div class="gradient-circle w-64 h-64 bg-gray-400 top-10 right-10 opacity-30"></div>
    <div class="gradient-circle w-64 h-56 bg-gray-400 bottom-10 left-10 opacity-30"></div>
    <div class="gradient-circle w-64 h-64 bg-orange-300 bottom-10 right-1/4 opacity-30"></div>

<body class="bg-black min-h-screen relative overflow-hidden" >
  <!-- Contenedor de burbujas -->
  <div class="bubble-container">
    <div class="bubble" style="left: 10%; animation-duration: 12s; animation-delay: 2s;"></div>
    <div class="bubble" style="left: 30%; animation-duration: 10s; animation-delay: 0s;"></div>
    <div class="bubble" style="left: 50%; animation-duration: 15s; animation-delay: 4s;"></div>
    <div class="bubble" style="left: 70%; animation-duration: 8s; animation-delay: 6s;"></div>
    <div class="bubble" style="left: 90%; animation-duration: 14s; animation-delay: 1s;"></div>
  </div>
  <!-- boton de retroceso -->
  <a href="../../../PUBLIC/index.php" class="fixed top-4 left-4 bg-orange-500 hover:bg-orange-800 shadow-lg shadow-orange-500/50 text-black font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center space-x-2 shadow-lg z-50">
            <i class="fas fa-arrow-left"></i>
            <span>Volver</span>
        </a>  
   <!-- Contenedor principal para centrar el login -->
   <div class="flex items-center justify-center min-h-screen relative z-10">
    <div class="w-full max-w-lg p-8">
      <div class="bg-black shadow-2xl shadow-orange-400/60 border-solid border-t-2 border-l-2 border-orange-400 p-10 rounded-lg backdrop-blur-lg shadow-lg space-y-6">
        <div class="text-center">
          <h2 class="font-bold text-3xl text-gray-200">Bienvenido de nuevo</h2>
          <p class="text-sm mt-2 text-orange-400">Inicia sesión para continuar</p>
        </div>
          
                    <form id="loginForm" action="login_escuela.php" method="post" class="space-y-6">
                      <div class="space-y-2">
                        <label for="usuario_correo" class="text-sm font-semibold text-orange-400">Correo Electrónico</label>
                        <div class="relative">
                            <input type="email" id="usuario_correo" name="usuario_correo" placeholder="Digita tu Correo" 
                                class="w-full px-4 py-3 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required />
                            <i class="fa fa-envelope absolute top-1/2 right-3 transform -translate-y-1/2 text-yellow-500"></i>
                        </div>
                    </div>
                        
          <div class="space-y-2 pb-2">
            <label for="usuario_password" class="text-sm font-semibold text-orange-400">Contraseña</label>
              <div class="relative">
                  <input type="password" id="usuario_password" name="usuario_password" placeholder="Introduce tu Contraseña" class="w-full px-4 py-3 bg-gray-900/70 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-white" required />
                  <i class="fa fa-lock absolute top-1/2 right-3 transform -translate-y-1/2 text-yellow-500"></i>
              </div>
         </div>
         <a href="../recovery.php" class="text-sm mt-2 text-orange-400">¿Olvidaste tu contraseña?</a>
             
           <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
             Iniciar Sesión
           </button>
                    </form>
                    
                  <div class="text-center">
                      <p class="text-sm text-gray-400">¿Eres nuevo? <a href="register.php" class="text-orange-400 hover:text-orange-300">Regístrate aquí</a></p>
                  </div>
            </div>
         </div>
       </div>
    </div>
    <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#F97316',
                    background: '#000000',
                    color: '#F97316'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../../../PUBLIC/index.php';
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message,
                    icon: 'error',
                    confirmButtonColor: '#F97316',
                    background: '#0D1117',
                    color: '#F97316'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al procesar la solicitud',
                icon: 'error',
                confirmButtonColor: '#F97316',
                background: '#0D1117',
                color: '#F97316'
            });
        });
    });
    </script>
</body>
</html> 