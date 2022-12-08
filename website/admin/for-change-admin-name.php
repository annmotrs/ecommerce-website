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
      require_once ('../db.php');
      
      if(isset($_SESSION['admin_id'])){
        $id = $_SESSION['admin_id'];
        //Обновляем имя администратора
        $update_admin_name = $conn->prepare('UPDATE shop_db.admins SET name = :name WHERE id=:id');
        $update_admin_name->bindParam(':name', $name);
        $update_admin_name->bindParam(':id', $id);
        $update_admin_name->execute();
        header("Location: admin-personal-data.php");
      }
  }
  catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
      echo 'ERROR: ' . $e->getMessage();
  }
}

?>