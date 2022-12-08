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
  <title>Пользователи</title>
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

  <section class="users container">
    <h1 class="title">Пользователи</h1>
    <div class="users__cards">

      <?php

        try {
          require_once ('../db.php');

          $admin_id = $_SESSION['admin_id'];
          $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
          $set_local->execute(); 

          //Выборка всех пользователей
          $select_users = $conn->prepare('SELECT * FROM shop_db.users');
          $select_users ->execute(); 
          
          if($select_users->rowCount() > 0 ) {
            $select_users_array = $select_users->fetchAll(); 

            foreach($select_users_array as $select_users_array_row) {
              $birthday = new DateTime($select_users_array_row['birthday']);
              $registration_date = new DateTime($select_users_array_row['registration_date']);

      ?>

      <div class="users__card">
        <p class="users__card-property">ID пользователя: <span class="users__card-value"><?= $select_users_array_row['id']; ?></span></p>
        <p class="users__card-property">Имя: <span class="users__card-value"><?= $select_users_array_row['name']; ?></span></p>
        <p class="users__card-property">Отчество: <span class="users__card-value"><?= $select_users_array_row['patronymic']; ?></span></p>
        <p class="users__card-property">Фамилия: <span class="users__card-value"><?= $select_users_array_row['surname']; ?></span></p>
        <p class="users__card-property">Пол: <span class="users__card-value"><?= $select_users_array_row['gender']; ?></span></p>
        <p class="users__card-property">День рождения: <span class="users__card-value"><?= $birthday->Format('d.m.Y'); ?></span></p>
        <p class="users__card-property">email: <span class="users__card-value"><?= $select_users_array_row['email']; ?></span></p>
        <p class="users__card-property">Город: <span class="users__card-value"><?= $select_users_array_row['city']; ?></span></p>
        <p class="users__card-property">Телефон: <span class="users__card-value"><?= $select_users_array_row['phone']; ?></span></p>
        <p class="users__card-property">Дата регистрации: <span class="users__card-value"><?= $registration_date->Format('d.m.Y, H:i'); ?></span></p>
      </div>

      <?php 
            }
          }  
        }
        catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
          echo 'ERROR: ' . $e->getMessage();
        }

      ?>

    </div>
  </section>

  
  <script src="../js/main.js"></script>
</body>
</html>