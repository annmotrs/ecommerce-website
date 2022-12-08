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
  <title>Администраторы</title>
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

  <section class="admins container">
    <h1 class="title">Администраторы</h1>
    <div class="admins__cards">

      <?php

        try {
          require_once ('../db.php');

          $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
          $set_local->execute(); 

          //Выборка всех администраторов
          $select_admins = $conn->prepare('SELECT * FROM shop_db.admins');
          $select_admins ->execute(); 
          
          if($select_admins->rowCount() > 0 ) {
            $select_admins_array = $select_admins->fetchAll(); 

            foreach($select_admins_array as $select_admins_array_row) {

      ?>

      <div class="admins__card">
        <p class="admins__card-property">ID администратора: <span class="admins__card-value"><?= $select_admins_array_row['id']; ?></span></p>
        <p class="admins__card-property">Имя: <span class="admins__card-value"><?= $select_admins_array_row['name']; ?></span></p>
        <p class="admins__card-property">Фамилия: <span class="admins__card-value"><?= $select_admins_array_row['surname']; ?></span></p>
        <p class="admins__card-property">email: <span class="admins__card-value"><?= $select_admins_array_row['email']; ?></span></p>
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