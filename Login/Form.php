<!DOCTYPE html>
<html lang="es">
<head>
  <?php $Ruta = ""; include_once 'Complementos/Logo_movil.php'; ?>
  <link rel="stylesheet" href="Login/EstiloSesion.css">
  <script src="https://code.jquery.com/jquery-3.7.1.js" type="text/javascript"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="css/eggy.css" />
  <link rel="stylesheet" href="css/progressbar.css" />
  <link rel="stylesheet" href="css/theme.css" />
  <title>Inicio de Sesión</title>
</head>

<body>
  <!-- Fondo partículas -->
  <div id="particles-js"></div>

  <!-- Contenedor centrado -->
  <div class="main-wrapper">
    <div class="login-container">
      <h2>Inicio de sesión</h2>
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" name="form">
        <div class="input-group">
          <span class="input-icon"><i class="fa fa-user"></i></span>
          <input type="text" id="1" name="Usuario" placeholder="Usuario">
        </div>

        <div class="input-group">
          <span class="input-icon"><i class="fa fa-lock"></i></span>
          <input type="password" id="2" name="Password" placeholder="Contraseña">
          <span class="input-icon2"><i id="eye" class="fa-regular fa-eye fa-xl"></i></span>
        </div>

        <button type="submit">Entrar</button>
      </form>

      <?php if (!empty($ErrorLogin)) { ?>
        <script type="module">
          var error = "<?php echo $ErrorLogin;?>";
          import { Eggy } from './js/eggy.js';
          await Eggy({title: 'Error!', message: error, type: 'error', duration: 5000});
        </script>
      <?php } ?>
    </div>
  </div>
  
  <script src="js/modulos.js"></script>
  <script src="js/particles.js"></script>
  <script src="js/app.js"></script>
</body>
</html>

<script>
  document.querySelectorAll('input').forEach(input => {
  input.addEventListener('focus', () => {
    setTimeout(() => {
      input.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 300);
  });
});

</script>

