<?php
session_start();

function check_input($data) {
    $data = trim($data); //Удаляет пробелы из начала и конца строки
    $data = stripslashes($data);//Удаляет экранирование символов
    $data = htmlspecialchars($data);// Преобразует специальные символы в HTML сущности. Например <a href='test'>Test</a> преобразует в &lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;
    return $data;
}

if (isset($_POST['insert-review'])){
  $grade = $_POST['grade'];
  $review = $_POST['review'];
  $product_id = $_POST['product-id'];

  $grade = check_input($grade);
  $review = check_input($review);
  $product_id = check_input($product_id);
  $user_id = $_SESSION['user_id'];

  //Работаем с базой данных
  try {
      require_once ('db.php');
      
      $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
      $set_local->execute(); 

      //Добавляем отзыв в таблицу
      $stmt = $conn->prepare('INSERT INTO shop_db.reviews (id, user_id, product_id, grade, description, date_created, date_updated, status) VALUES (NULL, :user_id, :product_id, :grade, :description, NOW(), NULL, "на проверке")');
      $stmt->bindParam(':user_id', $user_id);
      $stmt->bindParam(':product_id', $product_id);
      $stmt->bindParam(':grade', $grade);
      $stmt->bindParam(':description', $review);

      $stmt->execute();
      header("Location: user-reviews.php");

  }
  catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
      echo 'ERROR: ' . $e->getMessage();
  }
}
?>