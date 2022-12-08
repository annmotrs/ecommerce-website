<?php
session_start();

if(!isset($_SESSION['user_id'])) {
  header("Location: user-login.php");
}    
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Личный кабинет</title>
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

  <section class="user-settings">
    <div class="user-settings__wrapper">
      <h1 class="user-settings__title">Личный кабинет</h1>
      <div class="user-settings__settings-section-menu">
        <button class="user-settings__menu-item user-settings__menu-item1 user-settings__menu-item--active" type="button">Личные данные</button>
        <button class="user-settings__menu-item user-settings__menu-item2" type="button">Заказы</button>
        <button class="user-settings__menu-item user-settings__menu-item3" type="button">Отзывы</button>
      </div>
      <div class="user-settings__settings">
        <div class="user-settings__box-settings">
          <div class="user-settings__settings-section user-settings__settings-section1 user-settings__settings-section--active">
            <div class="user-settings__box-settings-title">
              <div class="user-settings__settings-title-icon"><i class="fa-solid fa-user-gear"></i></div>
              <h2 class="user-settings__settings-title">Личные данные</h2>
            </div>

            <?php

              try {
                require_once ('db.php');
              
                $id = $_SESSION['user_id'];
                $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
                $set_local->execute(); 
                
                //Делаем выборку записи с данными пользователя по его id
                $select_user = $conn->prepare('SELECT * FROM shop_db.users WHERE id=:id');
                $select_user ->bindParam(':id', $id);
                $select_user ->execute(); 

                if($select_user->rowCount() > 0 ) {
                  $select_user_array = $select_user->fetch(PDO::FETCH_ASSOC); 
                  $registration_date = new DateTime($select_user_array['registration_date']);
                  $birthday = new DateTime($select_user_array['birthday']);
                
            ?>
                <p class="user-settings__settings-info">ID пользователя: <span class="user-settings__settings-value"><?= $select_user_array['id']; ?></span></p>
                <p class="user-settings__settings-info">Дата регистрации: <span class="user-settings__settings-value"><?= $registration_date->Format('d.m.Y'); ?></span></p>
                <p class="user-settings__settings-info">Имя: <span class="user-settings__settings-value"><?= $select_user_array['name']; ?></span> <a href="change-name.php" class="user-settings-icon-update"><i class="fa-solid fa-pen-to-square"></i></a></p>
                <p class="user-settings__settings-info">Отчество: <span class="user-settings__settings-value"><?= $select_user_array['patronymic']; ?></span> <a href="change-patronymic.php" class="user-settings-icon-update"><i class="fa-solid fa-pen-to-square"></i></a></p>
                <p class="user-settings__settings-info">Фамилия: <span class="user-settings__settings-value"><?= $select_user_array['surname']; ?></span> <a href="change-surname.php" class="user-settings-icon-update"><i class="fa-solid fa-pen-to-square"></i></a></p>
                <p class="user-settings__settings-info">Пол: <span class="user-settings__settings-value"><?= $select_user_array['gender']; ?></span></p>
                <p class="user-settings__settings-info">День рождения: <span class="user-settings__settings-value"><?= $birthday->Format('d.m.Y'); ?></span></p>
                <p class="user-settings__settings-info">email: <span class="user-settings__settings-value"><?= $select_user_array['email']; ?></span> <a href="change-email.php" class="user-settings-icon-update"><i class="fa-solid fa-pen-to-square"></i></a></p>
                <p class="user-settings__settings-info">Город: <span class="user-settings__settings-value"><?= $select_user_array['city']; ?></span> <a href="change-city.php" class="user-settings-icon-update"><i class="fa-solid fa-pen-to-square"></i></a></p>
                <p class="user-settings__settings-info">Телефон: <span class="user-settings__settings-value"><?= $select_user_array['phone']; ?></span> <a href="change-phone.php" class="user-settings-icon-update"><i class="fa-solid fa-pen-to-square"></i></a></p>
              
            <?php  
                }  
              }
              catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
                    echo 'ERROR: ' . $e->getMessage();
              }
            ?>

          </div>
        </div>
        <div class="user-settings__box-settings">
          <div class="user-settings__settings-section user-settings__settings-section2">
            <div class="user-settings__box-settings-title">
              <div class="user-settings__settings-title-icon"><i class="fa-solid fa-cart-shopping"></i></div>
              <h2 class="user-settings__settings-title">Заказы</h2>
            </div>              
            <p class="user-settings__settings-info"><a href="active-orders.php" class="user-settings__settings-link">Активные заказы</a></p>
            <p class="user-settings__settings-info"><a href="history-orders.php" class="user-settings__settings-link">История заказов</a></p>
          </div>
          <div class="user-settings__settings-section user-settings__settings-section3">
            <div class="user-settings__box-settings-title">
              <div class="user-settings__settings-title-icon"><i class="fa-solid fa-message"></i></div>
              <h2 class="user-settings__settings-title">Отзывы</h2>
            </div>              
            <p class="user-settings__settings-info"><a href="user-reviews.php" class="user-settings__settings-link">Мои отзывы</a></p>
            <p class="user-settings__settings-info"><a href="purchases-without-reviews.php" class="user-settings__settings-link">Ожидают отзыва</a></p>
          </div>             
        </div>
      </div>
    </div>
  </section>
  
  <?php
  require "footer.php";
  ?>
  
  <script src="./js/main.js"></script>
  <script>
    const btnMenu = document.querySelectorAll('.user-settings__menu-item');
    const sectionMenu = document.querySelectorAll('.user-settings__settings-section');

    btnMenu.forEach((btn, index) => {
      btn.addEventListener('click', () => {
        sectionMenu.forEach((el, index) => {
          if(el.classList.contains('user-settings__settings-section--active')) {
            el.classList.remove('user-settings__settings-section--active');
            btnMenu[index].classList.remove('user-settings__menu-item--active');
          }
        });  
        sectionMenu[index].classList.add('user-settings__settings-section--active');
        btn.classList.add('user-settings__menu-item--active');
      });
    });

  </script>
</body>
</html>