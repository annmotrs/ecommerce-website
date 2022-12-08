<?php
session_start();
function check_input($data) {
  
    $data = trim($data); //Удаляет пробелы из начала и конца строки
    $data = stripslashes($data);//Удаляет экранирование символов
    $data = htmlspecialchars($data);// Преобразует специальные символы в HTML сущности

    return $data;
}

if(isset($_POST['change-city'])){
  $city = $_POST['new-city'];

  $city = check_input($city);

  //Работаем с базой данных

  try {
      require_once ('db.php');
      
      if(isset($_SESSION['user_id'])){
        $id = $_SESSION['user_id'];
        //Обновляем город пользователя
        $update_user_city = $conn->prepare('UPDATE shop_db.users SET city = :city WHERE id=:id');
        $update_user_city->bindParam(':city', $city);
        $update_user_city->bindParam(':id', $id);
        $update_user_city->execute();
        header("Location: user-settings.php");
      }
  }
  catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
      echo 'ERROR: ' . $e->getMessage();
  }
}

?>