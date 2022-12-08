<?php
session_start();
function check_input($data) {
  
    $data = trim($data); //Удаляет пробелы из начала и конца строки
    $data = stripslashes($data);//Удаляет экранирование символов
    $data = htmlspecialchars($data);// Преобразует специальные символы в HTML сущности

    return $data;
}

if(isset($_POST['change-phone'])){
  $phone = $_POST['new-phone'];

  $phone = check_input($phone);

  //Работаем с базой данных

  try {
      require_once ('db.php');
      
      if(isset($_SESSION['user_id'])){
        $id = $_SESSION['user_id'];
        //Обновляем телефон пользователя
        $update_user_phone = $conn->prepare('UPDATE shop_db.users SET phone = :phone WHERE id=:id');
        $update_user_phone->bindParam(':phone', $phone);
        $update_user_phone->bindParam(':id', $id);
        $update_user_phone->execute();
        header("Location: user-settings.php");
      }
  }
  catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
      echo 'ERROR: ' . $e->getMessage();
  }
}

?>