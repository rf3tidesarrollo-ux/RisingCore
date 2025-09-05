<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <link rel="shortcut icon" href="Images/MiniLogo.png">
  <script src="js/particles.js"></script>
  <link rel="stylesheet" href="css/eggy.css" />
  <link rel="stylesheet" href="css/progressbar.css" />
  <link rel="stylesheet" href="css/theme.css" />
  <link rel="stylesheet" href="Login/EstiloSesion.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>Inicio de Sesión</title>
</head>
<body>
  <div class="login-container">
    <h2>Inicio de sesión</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" name="form">
      <div class="input-group">
        <span class="input-icon"><i class="fa fa-user"></i></span>
        <input type="text" name="Usuario" placeholder="Usuario" required>
      </div>

      <div class="input-group">
        <span class="input-icon"><i class="fa fa-lock"></i></span>
        <input type="password" name="Password" placeholder="Contraseña" required>
      </div>

      <button>Entrar</button>
    </form>
  </div>
  <?php if (!empty($ErrorLogin)) { ?>
            <script type="module">
                var error="<?php echo $ErrorLogin;?>";
                import { Eggy } from 'js/eggy.js';
                await Eggy({title: 'Error!', message: error, type: 'error',duration: 5000});
            </script>
        <?php } ?>
        <script src="js/modulos.js"></script>
    <script src="js/app.js"></script>
</body>
</html>
