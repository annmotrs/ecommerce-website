<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Регистрация</title>
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

  <section class="user-register">
    <div class="user-register__wrapper">
      <h1 class="user-register__title title">Регистрация</h1>
      <form action="for-user-register.php" method="POST" class="user-register__form" id="form">
        <div class="user-register__input-groups">
          <label class="user-register__label">Имя<input type="text" class="user-register__input" name="name" pattern="^[А-Яа-яЁё]+$" title="Имя должно быть только с использованием русских букв." placeholder="Иван" required></label>
          <label class="user-register__label">Отчество<input type="text" class="user-register__input" name="patronymic" pattern="^[А-Яа-яЁё]+$" title="Отчество должно быть только с использованием русских букв." placeholder="Иванович" required></label>
          <label class="user-register__label">Фамилия<input type="text" class="user-register__input" name="surname" pattern="^[А-Яа-яЁё]+$" title="Фамилия должна быть только с использованием русских букв." placeholder="Иванов" required></label>
          <div class="user-register__input-group">
            <label class="user-register__label">Пол</label> 
            <label class="user-register__label user-register__label-radio">
              <input class="user-register__radio" type="radio" name="gender" value="мужской" required>
              <span class="user-register__radio-style"></span>Мужской
            </label> 
            <label class="user-register__label user-register__label-radio">
              <input class="user-register__radio" type="radio" name="gender" value="женский">
              <span class="user-register__radio-style"></span>Женский
            </label>            
          </div>
          <label class="user-register__label">Дата рождения<input type="date" class="user-register__input" name="birthday" required></label>
          <label class="user-register__label">Город<input type="text" class="user-register__input" name="city" pattern="^[А-Яа-яЁё]+$" title="Город должен быть только с использованием русских букв." placeholder="Москва" required></label>
          <label class="user-register__label">Email<input type="email" class="user-register__input" name="email" placeholder="ivanivanov@gmail.com" required></label>
          <label class="user-register__label">Телефон<input type="tel" class="user-register__input" name="phone" id="phone" placeholder="+7 (985) 875-75-75" required></label>
          <label class="user-register__label">Пароль<input type="password" class="user-register__input" name="password" pattern="[A-Za-z0-9]{8,}" title="От 8 символов с использованием цифр и латинских букв." required></label>
        </div>
        <button type="submit" class="button" name="user-register">Зарегистрироваться</button>
      </form>
    </div>
  </section>
  
  <?php
  require "footer.php";
  ?>
  
  <script src="./js/main.js"></script>
  <script src="https://unpkg.com/imask"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="./js/userRegister.js"></script>
</body>
</html>