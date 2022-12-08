<?php
session_start();

$is_admin_not_found = false;

function check_input($data) {
  
    $data = trim($data); //Удаляет пробелы из начала и конца строки
    $data = stripslashes($data);//Удаляет экранирование символов
    $data = htmlspecialchars($data);// Преобразует специальные символы в HTML сущности

    return $data;
}

if(isset($_POST['new-email'])){
  $email = $_POST['new-email'];

  $email = check_input($email);

  //Работаем с базой данных

  try {
      require_once ('../db.php');
      
      if(isset($_SESSION['admin_id'])){
        $id = $_SESSION['admin_id'];

        //Ищем есть ли уже администратор с этим email
        $select_admin = $conn->prepare('SELECT * FROM shop_db.admins WHERE email=:email'); 
        $select_admin ->bindParam(':email', $email);
        $select_admin ->execute(); 

        //Если нет, то обновляем email
        if($select_admin->rowCount() === 0) {
          //Обновляем email администратора
          $update_admin_email = $conn->prepare('UPDATE shop_db.admins SET email = :email WHERE id=:id');
          $update_admin_email->bindParam(':email', $email);
          $update_admin_email->bindParam(':id', $id);
          $update_admin_email->execute();
          $is_admin_not_found = true;
        }
        else {
          $is_admin_not_found = false;
        }  
      }
  }
  catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
      echo 'ERROR: ' . $e->getMessage();
  }
}

echo json_encode($is_admin_not_found);

?>