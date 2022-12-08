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
  <title>Личные данные</title>
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

  <section class="admin-data">
    <div class="admin-data__wrapper">
      <h1 class="admin-data__title title">Личные данные</h1>
      <?php

      try {
        require_once ('../db.php');

        $id = $_SESSION['admin_id'];
        $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
        $set_local->execute(); 

        //Делаем выборку записи по id администратора
        $select_admin = $conn->prepare('SELECT * FROM shop_db.admins WHERE id=:id');
        $select_admin ->bindParam(':id', $id);
        $select_admin ->execute(); 

        if($select_admin->rowCount() > 0 ) {
          $select_admin_array = $select_admin->fetch(PDO::FETCH_ASSOC); ;
        
      ?>

        <p class="admin-data__property">ID администратора: <span class="admin-data__value"><?= $select_admin_array['id']; ?></span></p>
        <p class="admin-data__property">Имя: <span class="admin-data__value"><?= $select_admin_array['name']; ?></span> <a href="change-admin-name.php" class="admin-data__icon-update"><i class="fa-solid fa-pen-to-square"></i></a></p>
        <p class="admin-data__property">Фамилия: <span class="admin-data__value"><?= $select_admin_array['surname']; ?></span> <a href="change-admin-surname.php" class="admin-data__icon-update"><i class="fa-solid fa-pen-to-square"></i></a></p>
        <p class="admin-data__property">email: <span class="admin-data__value"><?= $select_admin_array['email']; ?></span> <a href="change-admin-email.php" class="admin-data__icon-update"><i class="fa-solid fa-pen-to-square"></i></a></p>

      <?php  
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