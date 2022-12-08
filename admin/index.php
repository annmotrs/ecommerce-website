<?php
session_start();

if(isset($_SESSION['admin_id'])) {
  header("Location: dashboard.php");
}    
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Вход</title>
  <link rel="icon" type="image/x-icon" href="../images/icon.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link rel="stylesheet" href="../css/admin-style.css">
  <link rel="stylesheet" href="../css/admin-media.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
</head>
<body>

  <section class="admin-login">
    <div class="admin-login__wrapper">
      <h1 class="admin-login__title title">Вход</h1>
      <form action="" method="POST" class="admin-login__form" id="form">
        <div class="admin-login__input-groups">
          <label class="admin-login__label">Email<input type="email" class="admin-login__input" name="email" required></label>
          <label class="admin-login__label">Пароль<input type="password" class="admin-login__input" name="password" required></label>
        </div>
        <button type="submit" class="button" name="admin-login">Войти</button>
      </form>
    </div>
  </section>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="../js/adminLogin.js"></script>
</body>
</html>