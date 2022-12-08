<?php
session_start();

$is_admin_found = false;

function check_input($data) {
  $data = trim($data); //Удаляет пробелы из начала и конца строки
  $data = stripslashes($data);//Удаляет экранирование символов
  $data = htmlspecialchars($data);// Преобразует специальные символы в HTML сущности. Например <a href='test'>Test</a> преобразует в &lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;
  return $data;
}

if(isset($_POST['email']) && isset($_POST['password'])){
  $email = $_POST['email'];
  $password = $_POST['password'];

  $email = check_input($email);
  $password = check_input($password);

  //Работаем с базой данных
  try {
      
      require_once('../db.php');
      $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); // подготовка запроса SQL на установку русской локации
      $set_local->execute(); // выполняем запрос

      $select_admin = $conn->prepare('SELECT * FROM shop_db.admins WHERE email=:email'); //Готовим запрос на выборку всех полей из таблицы, где совпадет email администратора
      $select_admin ->bindParam(':email', $email);
      $select_admin ->execute(); //Выполняем запрос
      
      if($select_admin->rowCount() > 0 ) {
        $select_admin_array = $select_admin->fetch(PDO::FETCH_ASSOC); 
        if(password_verify($password, $select_admin_array['password'])) {
          $id = $select_admin_array['id'];
          $_SESSION['admin_id'] = $id; //сохраняем обьект администратора в сессии
          $is_admin_found = true;
        }
        else {
          $is_admin_found = false;    
        }
      }
      else {
        $is_admin_found = false;
      }
    
  }
  catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
      echo 'ERROR: ' . $e->getMessage();
  }
}

echo json_encode($is_admin_found);

?>