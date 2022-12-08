<?php
session_start();

$is_user_not_found = false;

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
      require_once ('db.php');
      
      if(isset($_SESSION['user_id'])){
        $id = $_SESSION['user_id'];

        //Ищем есть ли уже пользователь с полученным email
        $select_user = $conn->prepare('SELECT * FROM shop_db.users WHERE email=:email'); 
        $select_user ->bindParam(':email', $email);
        $select_user ->execute(); 
        
        //Если нет, то обновляем email
        if($select_user->rowCount() === 0) {
          //Обновляем email пользователя
          $update_user_email = $conn->prepare('UPDATE shop_db.users SET email = :email WHERE id=:id');
          $update_user_email->bindParam(':email', $email);
          $update_user_email->bindParam(':id', $id);
          $update_user_email->execute();
          $is_user_not_found = true;
        }
        else {
          $is_user_not_found = false;
        }  
      }
  }
  catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
      echo 'ERROR: ' . $e->getMessage();
  }
}

echo json_encode($is_user_not_found);

?>