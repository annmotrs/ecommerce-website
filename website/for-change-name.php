<?php
session_start();
function check_input($data) {
  
    $data = trim($data); //Удаляет пробелы из начала и конца строки
    $data = stripslashes($data);//Удаляет экранирование символов
    $data = htmlspecialchars($data);// Преобразует специальные символы в HTML сущности

    return $data;
}

if(isset($_POST['change-name'])){
  $name = $_POST['new-name'];

  $name = check_input($name);

  //Работаем с базой данных

  try {
      require_once ('db.php');
      
      if(isset($_SESSION['user_id'])){
        $id = $_SESSION['user_id'];
        //Обновляем имя пользователя
        $update_user_name = $conn->prepare('UPDATE shop_db.users SET name = :name WHERE id=:id');
        $update_user_name->bindParam(':name', $name);
        $update_user_name->bindParam(':id', $id);
        $update_user_name->execute();
        header("Location: user-settings.php");
      }
  }
  catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
      echo 'ERROR: ' . $e->getMessage();
  }
}

?>