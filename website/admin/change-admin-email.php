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
  <title>Редактирование</title>
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
      <h1 class="admin-data__title title">Редактирование email</h1>
      <form action="for-change-admin-email.php" method="POST" class="admin-data__form" id="form">
        <p><label class="admin-data__label" for="email">Новый email:</label></p>

      <?php

      try {
        require_once ('../db.php');

        $id = $_SESSION['admin_id'];
        $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
        $set_local->execute(); 

        //Находим данные администратора по его id
        $select_admin = $conn->prepare('SELECT * FROM shop_db.admins WHERE id=:id');
        $select_admin ->bindParam(':id', $id);
        $select_admin ->execute(); 

        if($select_admin->rowCount() > 0 ) {
          $select_admin_array = $select_admin->fetch(PDO::FETCH_ASSOC); ;
        
      ?>

        <p><input class="admin-data__input" type="email" name="new-email" id="email" value="<?= $select_admin_array['email']; ?>" required></p>  

      <?php  
        }  
      }
      catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
            echo 'ERROR: ' . $e->getMessage();
      }
      ?>

        <div class="admin-data__box-button">
          <button type="submit" class="button" name="change-email">Сохранить</button>
        </div>
      </form>
      <div class="admin-data__box-link">
        <a href="admin-personal-data.php" class="admin-data__link">Вернуться в личный кабинет</a>
      </div>  
    </div>
  </section>

  
  <script src="../js/main.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="../js/changeAdminEmail.js"></script>
 
</body>
</html>