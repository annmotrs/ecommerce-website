<?php

$last_order_id = 0;

function check_input($data) {
    $data = trim($data); //Удаляет пробелы из начала и конца строки
    $data = stripslashes($data);//Удаляет экранирование символов
    $data = htmlspecialchars($data);// Преобразует специальные символы в HTML сущности. Например <a href='test'>Test</a> преобразует в &lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;
    return $data;
}

if(isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['email']) && isset($_POST['password'])){
  $name = $_POST['name'];
  $surname = $_POST['surname'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  $name = check_input($name);
  $surname = check_input($surname);
  $email = check_input($email);
  $password = check_input($password);
  $password = password_hash($password, PASSWORD_DEFAULT);

  //Работаем с базой данных
  try {
      require_once ('../db.php');
      
      $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); // подготовка запроса SQL на установку русской локации
      $set_local->execute(); // выполняем запрос

      $select_admin = $conn->prepare('SELECT * FROM shop_db.admins WHERE email=:email'); //Готовим запрос на выборку всех полей из таблицы, где совпадет email
      $select_admin ->bindParam(':email', $email);// Говорим откуда брать значение связанного параметра :email. Так мы защищаемся от SQL инъекций
      $select_admin ->execute(); //Выполняем запрос
      $select_admin_array = $select_admin->fetchAll(); //Получаем ассоциативный массив значений

    if( count($select_admin_array) == 0 ) {
      //Добавляем администратора
      $stmt = $conn->prepare('INSERT INTO shop_db.admins (id, name, surname, email, password) VALUES (NULL, :name, :surname, :email, :password)');
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':surname', $surname);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':password', $password);

      $stmt->execute();
      $last_order_id = $conn->lastInsertId();
    }

  }
  catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
      echo 'ERROR: ' . $e->getMessage();
  }
}

echo json_encode($last_order_id);

?>