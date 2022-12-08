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
  <title>Редактирование</title>
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

  <section class="change-user-data">
    <div class="change-user-data__wrapper">
      <h1 class="change-user-data__title">Редактирование email</h1>
      <form action="for-change-email.php" method="POST" class="change-user-data__form" id="form">
        <p><label class="change-user-data__label" for="email">Новый email:</label></p>

        <?php

          try {
            require_once ('db.php');

            $id = $_SESSION['user_id'];
            $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
            $set_local->execute(); 
            
            //Находим данные пользователя по его id
            $select_user = $conn->prepare('SELECT * FROM shop_db.users WHERE id=:id');
            $select_user ->bindParam(':id', $id);
            $select_user ->execute(); 

            if($select_user->rowCount() > 0 ) {
              $select_user_array = $select_user->fetch(PDO::FETCH_ASSOC); 
        ?>  

        <p><input class="change-user-data__input" type="email" name="new-email" id="email" value="<?= $select_user_array['email']; ?>" required></p>  

        <?php  
          }  
        }
        catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
              echo 'ERROR: ' . $e->getMessage();
        }

        ?>   

        <div class="change-user-data__box-button">
          <button type="submit" class="button" name="change-email">Сохранить</button>
        </div>
      </form>
      <div class="change-user-data__box-link">
        <a href="user-settings.php" class="change-user-data__link">Вернуться в личный кабинет</a>
      </div>  
    </div>
  </section>
  
  <?php
  require "footer.php";
  ?>
  
  <script src="./js/main.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="./js/changeUserEmail.js"></script>

</body>
</html>