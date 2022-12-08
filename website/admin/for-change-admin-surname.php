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
      require_once ('../db.php');
      
      if(isset($_SESSION['admin_id'])){
        $id = $_SESSION['admin_id'];
        //Обновляем фамилию администратора
        $update_admin_surname = $conn->prepare('UPDATE shop_db.admins SET surname = :surname WHERE id=:id');
        $update_admin_surname->bindParam(':surname', $surname);
        $update_admin_surname->bindParam(':id', $id);
        $update_admin_surname->execute();
        header("Location: admin-personal-data.php");
      }
  }
  catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
      echo 'ERROR: ' . $e->getMessage();
  }
}

?>