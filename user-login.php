<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Вход</title>
  <link rel="icon" type="image/x-icon" href="./images/icon.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/media.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
</head>
<body>

  <?php
  require "header.php";
  ?>

  <section class="user-login">
    <div class="user-login__wrapper">
      <h1 class="user-login__title title">Вход</h1>
      <form action="for-user-login.php" method="POST" class="user-login__form" id="form">
        <div class="user-login__input-groups">
          <label class="user-login__label">Email<input type="email" class="user-login__input" name="email" required></label>
          <label class="user-login__label">Пароль<input type="password" class="user-login__input" name="password" required></label>
        </div>
        <button type="submit" class="button" name="user-login">Войти</button>
      </form>
      <p class="user-login__text">Не имеете аккаунта? <a href="user-register.php" class="user-login__link">Зарегистрируйтесь сейчас</a></p>
      
    </div>
  </section>
  
  <?php
  require "footer.php";
  ?>
  
  <script src="./js/main.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="./js/userLogin.js"></script>
</body>
</html>