<?php
require "conexion.php";

$token = isset($_GET['token']) ? $_GET['token'] : null;
$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : null;
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : null;
    $token = isset($_POST['token']) ? $_POST['token'] : null;

    if ($new_password && $confirm_password && $token) {
        if ($new_password === $confirm_password) {
            // Verificar si el token es válido y no ha expirado
            $stmt = $conn->prepare("SELECT usuario_documento FROM recuperacion_cuentas WHERE token = :token AND fecha_expiracion > NOW()");
            $stmt->execute(['token' => $token]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $usuario_documento = $result['usuario_documento'];

                // Actualizar la contraseña
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE usuarios SET usuario_password = :password WHERE usuario_documento = :documento";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->execute(['password' => $hashed_password, 'documento' => $usuario_documento]);

                // Eliminar el token usado
                $delete_sql = "DELETE FROM recuperacion_cuentas WHERE token = :token";
                $delete_stmt = $conn->prepare($delete_sql);
                $delete_stmt->execute(['token' => $token]);

                    $success = "Tu contraseña ha sido actualizada exitosamente. Ahora puedes iniciar sesión con tu nueva contraseña.";
                } else {
                    $error = "Ocurrió un error al actualizar la contraseña. Por favor, inténtalo de nuevo.";
                }
            } else {
                $error = "El enlace para restablecer la contraseña ha expirado o no es válido.";
            }
        } else {
            $error = "Las contraseñas no coinciden.";
        }
    } else {
        $error = "Por favor, completa todos los campos.";
    }

?>

<!DOCTYPE html>
<html data-theme="black" lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Perfil - Classment Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.4/dist/full.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    </head>
    <body>
    <body class="bg-black min-h-screen relative overflow-hidden justify-center">

  <!-- Navbar -->
  <div class="navbar bg-transparent py-4 backdrop-blur-md bg-opacity-50 fixed top-0 left-0 right-0 z-50 shadow-lg">
    <div class="navbar-start flex justify-center">
      <div class="dropdown">
        <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
          </svg>
        </div>
        <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
          <li><a href="../index.php">Inicio</a></li>
          <li>
            <a>Servicios</a>
            <ul class="p-2">
              <li><a href="../index.php#title-schools">Escuelas</a></li>
              <li><a href="../index.php#title-courses">Cursos</a></li>
              </ul>
          </li>
          <li><a href="../index.php#nosotros">Nosotros</a></li>
        </ul>
      </div>
      <a href="../index.php" class="btn btn-ghost">
        <img src="../IMG/logo.png" alt="logo" class="w-f h-full" data-aos="zoom-in">
        <h1 class="text-4xl font-bold" data-aos="zoom-in">Classment Academy</h1>
      </a>
    </div>
    <div class="navbar-center hidden lg:flex" data-aos="zoom-in">
      <ul class="menu menu-horizontal px-1" data-aos="zoom-in">
      <li><a href="../index.php">Inicio</a></li>
      <li>
          <details>
            <summary>Servicios</summary>
            <ul class="p-2">
            <li><a href="../index.php#title-schools">Escuelas</a></li>
            <li><a href="../index.php#title-courses">Cursos</a></li>
            </ul>
          </details>
        </li>
        <li><a href="../index.php#nosotros">Nosotros</a></li>
      </ul>
    </div>
  </div>
  <main class="pt-24">
        <div class="container mx-auto p-4">
            <div class="flex items-center justify-center">
                <div class="w-full max-w-5xl p-8">
                    <div class="bg-black pr-10 rounded-lg backdrop-blur-lg shadow-lg space-y-6">   
                        <div class="flex flex-row items-center ">
                            <div class="w-1/2  flex justify-start">
                                <img
                                src="../IMG/design/futbolista.png"
                                class="max-w-sm pr-10 h-auto" data-aos="zoom-in" />
                            </div>
                            <div class="w-1/2 px-10 mt-4">
                                <form method="POST" action="">
                                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                                    <h2 class="text-4xl tracking-wide font-extrabold text-center">Restablecer Contraseña</h2>
                                    <div class="form-control mt-6">
                                        <label class="label">
                                            <span class="label-text">Nueva Contraseña</span>
                                        </label>
                                        <input type="password" placeholder="Ingresa tu nueva contraseña" class="input input-bordered" name="new_password" required />
                                    </div>
                                    <div class="form-control mt-6">
                                        <label class="label">
                                            <span class="label-text">Confirmar Nueva Contraseña</span>
                                        </label>
                                        <input type="password" placeholder="Confirma tu nueva contraseña" class="input input-bordered" name="confirm_password" required />
                                    </div>
                                    <div class="mt-6">
                                        <button class="w-full btn btn-lg bg-orange-500 " type="submit">Restablecer Contraseña</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000
    });
  </script>
</body>

</html>



