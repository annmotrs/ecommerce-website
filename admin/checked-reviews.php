<?php
session_start();

if(!isset($_SESSION['admin_id'])) {
  header("Location: index.php");
}    
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Проверенные отзывы</title>
  <link rel="icon" type="image/x-icon" href="../images/icon.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link rel="stylesheet" href="../css/admin-style.css">
  <link rel="stylesheet" href="../css/admin-media.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
</head>
<body>

  <?php
    require "admin-header.php";
  ?>

  <section class="reviews container">
    <h1 class="title">Проверенные отзывы</h1>
    <div class="reviews__cards">

      <?php 
          try {
            require_once ('../db.php');

            $admin_id = $_SESSION['admin_id'];
            $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
            $set_local->execute(); 
    
            //Выборка всех отзывов со статусом "опубликовано", "отклонено"
            $select_reviews = $conn->prepare('SELECT users.name, users.surname, products.title, reviews.* FROM shop_db.reviews 
                                              JOIN shop_db.users ON shop_db.reviews.user_id = shop_db.users.id 
                                              JOIN shop_db.products ON shop_db.reviews.product_id = shop_db.products.id 
                                              WHERE shop_db.reviews.status = "опубликовано" OR shop_db.reviews.status = "отклонено"
                                              ORDER BY shop_db.reviews.date_created DESC');
            $select_reviews ->execute(); 
    
            if($select_reviews->rowCount() > 0 ) {
              $select_reviews_array = $select_reviews->fetchAll(); 

              foreach($select_reviews_array as $select_reviews_array_row) {
                $date_created = new DateTime($select_reviews_array_row['date_created']);
                $date_updated = new DateTime($select_reviews_array_row['date_updated']);
                $date_updated = $date_updated->Format('d.m.Y, H:i');

      ?>

      <div class="reviews__card">
        <p class="reviews__card-date"><?= $date_created->Format('d.m.Y, H:i'); ?> <?= $select_reviews_array_row['date_updated'] ? "<br>(изм. $date_updated)" : ""; ?></p>
        <p class="reviews__card-property">ID отзыва: <span class="reviews__card-value"><?= $select_reviews_array_row['id']; ?></span></p>
        <p class="reviews__card-property">Статус: <span class="reviews__card-value <?= $select_reviews_array_row['status'] === "опубликовано" ? "reviews__card-value--color_green" : "reviews__card-value--color_red" ?>"><?= $select_reviews_array_row['status']; ?></span></p>
        <p class="reviews__card-property">ID товара: <span class="reviews__card-value"><?= $select_reviews_array_row['product_id']; ?></span></p>
        <p class="reviews__card-property">Название: <span class="reviews__card-value"><?= $select_reviews_array_row['title']; ?></span></p>
        <p class="reviews__card-property">ID пользователя: <span class="reviews__card-value"><?= $select_reviews_array_row['user_id']; ?></span></p>
        <p class="reviews__card-property">Фамилия: <span class="reviews__card-value"><?= $select_reviews_array_row['surname']; ?></span></p>
        <p class="reviews__card-property">Имя: <span class="reviews__card-value"><?= $select_reviews_array_row['name']; ?></span></p>
        <p class="reviews__card-property">Оценка: <span class="reviews__grade"><?= str_repeat("★", $select_reviews_array_row['grade']); ?><span class="reviews__grade-null"><?= str_repeat("★", 5 - $select_reviews_array_row['grade']); ?></span></span></p>
        <p class="reviews__card-property">Комментарий:</p>
        <p class="reviews__card-comment"><?= $select_reviews_array_row['description']; ?></p>
      </div>

      <?php 
            }
          }  
        }
        catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
          echo 'ERROR: ' . $e->getMessage();
        }

      ?>

    </div>
  </section>

  
  <script src="../js/main.js"></script>
</body>
</html>