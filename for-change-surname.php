<?php
session_start();
function check_input($data) {
  
    $data = trim($data); //Удаляет пробелы из начала и конца строки
    $data = stripslashes($data);//Удаляет экранирование символов
    $data = htmlspecialchars($data);// Преобразует специальные символы в HTML сущности

    return $data;
}

if(isset($_POST['change-surname'])){
  $surname = $_POST['new-surname'];

  $surname = check_input($surname);

  //Работаем с базой данных

  try {
      require_once ('db.php');
      
      if(isset($_SESSION['user_id'])){
        $id = $_SESSION['user_id'];
        //Обновляем фамилию пользователя
        $update_user_surname = $conn->prepare('UPDATE shop_db.users SET surname = :surname WHERE id=:id');
        $update_user_surname->bindParam(':surname', $surname);
        $update_user_surname->bindParam(':id', $id);
        $update_user_surname->execute();
        header("Location: user-settings.php");
      }
  }
  catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
      echo 'ERROR: ' . $e->getMessage();
  }
}

?>