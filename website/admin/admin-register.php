<?php
session_start();

if(!isset($_SESSION['admin_id'])) {
  header("Location: index.php");
}    
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Регистрация администратора</title>
  <link rel="icon" type="image/x-icon" href="../images/icon.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link rel="stylesheet" href="../css/admin-style.css">
  <link rel="stylesheet" href="../css/admin-media.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
</head>
<body>

  <?php
    require "admin-header.php";
  ?>

  <section class="admin-register">
    <div class="admin-register__wrapper">
      <h1 class="admin-register__title title">Регистрация администратора</h1>
      <form action="for-admin-register.php" method="POST" class="admin-register__form" id="form">
        <div class="admin-register__input-groups">
          <label class="admin-register__label">Имя<input type="text" class="admin-register__input" name="name" pattern="^[А-Яа-яЁё]+$" title="Имя должно быть только с использованием русских букв." placeholder="Иван" required></label>
          <label class="admin-register__label">Фамилия<input type="text" class="admin-register__input" name="surname" pattern="^[А-Яа-яЁё]+$" title="Фамилия должна быть только с использованием русских букв." placeholder="Иванов" required></label>
          <label class="admin-register__label">Email<input type="email" class="admin-register__input" name="email" placeholder="ivanivanov@gmail.com" required></label>
          <label class="admin-register__label">Пароль<input type="password" class="admin-register__input" name="password" pattern="[A-Za-z0-9]{8,}" title="От 8 символов с использованием цифр и латинских букв." required></label>
        </div>
        <button type="submit" class="button" name="admin-register">Зарегистрировать</button>
      </form>
    </div>
  </section>

  
  <script src="../js/main.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="../js/adminRegister.js"></script>
</body>
</html>