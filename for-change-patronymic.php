<?php
session_start();
function check_input($data) {
  
    $data = trim($data); //Удаляет пробелы из начала и конца строки
    $data = stripslashes($data);//Удаляет экранирование символов
    $data = htmlspecialchars($data);// Преобразует специальные символы в HTML сущности

    return $data;
}

if(isset($_POST['change-patronymic'])){
  $patronymic = $_POST['new-patronymic'];

  $patronymic = check_input($patronymic);

  //Работаем с базой данных

  try {
      require_once ('db.php');
      
      if(isset($_SESSION['user_id'])){
        $id = $_SESSION['user_id'];
        //Обновляем отчество пользователя
        $update_user_patronymic = $conn->prepare('UPDATE shop_db.users SET patronymic = :patronymic WHERE id=:id');
        $update_user_patronymic->bindParam(':patronymic', $patronymic);
        $update_user_patronymic->bindParam(':id', $id);
        $update_user_patronymic->execute();
        header("Location: user-settings.php");
      }
  }
  catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
      echo 'ERROR: ' . $e->getMessage();
  }
}

?>