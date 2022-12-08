<?php

$is_user_found = false;

function check_input($data) {
    $data = trim($data); //Удаляет пробелы из начала и конца строки
    $data = stripslashes($data);//Удаляет экранирование символов
    $data = htmlspecialchars($data);// Преобразует специальные символы в HTML сущности. Например <a href='test'>Test</a> преобразует в &lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;
    return $data;
}

if(isset($_POST['name']) && isset($_POST['patronymic']) && isset($_POST['surname']) && isset($_POST['gender']) && isset($_POST['birthday']) && isset($_POST['city']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['password'])){
  $name = $_POST['name'];
  $patronymic = $_POST['patronymic'];
  $surname = $_POST['surname'];
  $gender = $_POST['gender'];
  $birthday = $_POST['birthday']; 
  $city = $_POST['city'];
  $email = $_POST['email'];
  $phone = $_POST['phone']; 
  $password = $_POST['password'];

  $name = check_input($name);
  $patronymic = check_input($patronymic);
  $surname = check_input($surname);
  $gender = check_input($gender);
  $birthday = check_input($birthday);
  $city = check_input($city);
  $email = check_input($email);
  $phone = check_input($phone);
  $password = check_input($password);
  $password = password_hash($password, PASSWORD_DEFAULT);

  //Работаем с базой данных
  try {
      require_once ('db.php');
      
      $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); // подготовка запроса SQL на установку русской локации
      $set_local->execute(); // выполняем запрос

      $select_user = $conn->prepare('SELECT * FROM shop_db.users WHERE email=:email'); //Готовим запрос на выборку всех полей из таблицы, где совпадет email
      $select_user ->bindParam(':email', $email);// Говорим откуда брать значение связанного параметра :email. Так мы защищаемся от SQL инъекций
      $select_user ->execute(); //Выполняем запрос
      $select_user_array = $select_user->fetchAll(); //Получаем ассоциативный массив значений

    if( count($select_user_array) == 0 ) {
      //Добавляем пользователя
      $stmt = $conn->prepare('INSERT INTO shop_db.users (id, surname, name, patronymic,	gender, birthday, city, email, phone, password, registration_date) VALUES (NULL, :surname, :name, :patronymic, :gender, :birthday, :city, :email, :phone, :password, NOW())');
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':surname', $surname);
      $stmt->bindParam(':patronymic', $patronymic);
      $stmt->bindParam(':gender', $gender);
      $stmt->bindParam(':birthday', $birthday);
      $stmt->bindParam(':city', $city);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':phone', $phone);
      $stmt->bindParam(':password', $password);

      $stmt->execute();
      $is_user_found = true; 
    }
    else {
      $is_user_found = false; 
    }

  }
  catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
      echo 'ERROR: ' . $e->getMessage();
  }
}

echo json_encode($is_user_found);

?>