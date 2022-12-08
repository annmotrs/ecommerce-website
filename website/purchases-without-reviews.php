<?php
session_start();

if(!isset($_SESSION['user_id'])) {
  header("Location: user-login.php");
}    
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ожидают отзыва</title>
  <link rel="icon" type="image/x-icon" href="./images/icon.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/media.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
</head>
<body>

  <?php
  require "header.php";
  ?>

  <section class="purchases-without-reviews">
    <div class="purchases-without-reviews__wrapper">
      <h1 class="purchases-without-reviews__title">Ожидают отзыва</h1>
        <a href="user-settings.php" class="purchases-without-reviews__link">Вернуться в личный кабинет</a>
        <div class="purchases-without-reviews__products">

        <?php

        try {
          require_once ('db.php');

          $user_id = $_SESSION['user_id'];
          $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
          $set_local->execute(); 

          //Делаем выборку всех купленных товаров авторизованным пользователем, на которые он не писал отзыв
          $select_user_reviews = $conn->prepare('SELECT products.id, products.photo, products.title FROM shop_db.orders 
                                                JOIN shop_db.orders_products ON shop_db.orders_products.order_id = shop_db.orders.id 
                                                JOIN shop_db.products ON shop_db.orders_products.product_id = shop_db.products.id 
                                                WHERE orders.user_id=:user_id1 AND orders.status = "выполнен" AND
                                                NOT EXISTS (SELECT * FROM shop_db.reviews WHERE reviews.user_id=:user_id2 AND products.id = reviews.product_id)
                                                GROUP BY products.id ORDER BY orders.date_order DESC, orders_products.id');
          $select_user_reviews ->bindParam(':user_id1', $user_id);
          $select_user_reviews ->bindParam(':user_id2', $user_id);
          $select_user_reviews ->execute(); 
          
          
          if($select_user_reviews->rowCount() > 0 ) {
            $select_user_reviews_array = $select_user_reviews->fetchAll(); 

            //Выводим товары в цикле
            foreach($select_user_reviews_array as $select_user_reviews_array_row) {
          
        ?>

          <form action="create-review.php" method="POST" class="purchases-without-reviews__product">
            <div class="purchases-without-reviews__product-image"><img src="./uploads/<?= $select_user_reviews_array_row['photo']; ?>" alt="<?= $select_user_reviews_array_row['title'] ?>"></div>
            <div class="purchases-without-reviews__product-title"><a href="book.php?product_id=<?= $select_user_reviews_array_row['id']; ?>" class="purchases-without-reviews__product-title-link"><?= $select_user_reviews_array_row['title'] ?></a></div>
            <input type="hidden" name="product-id" value="<?= $select_user_reviews_array_row['id']; ?>">
            <input type="hidden" name="title" value="<?= $select_user_reviews_array_row['title']; ?>">
            <button type="submit" class="purchases-without-reviews__button button" name="create-review">Написать отзыв</button>
          </form>

        <?php 

            }  
          } 
          else {
            echo '<h1 class="reviews__message">Товаров нет</h1>';
          }
        } catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
          echo 'ERROR: ' . $e->getMessage();
        }    
        
        ?>

        </div>
    </div>
  </section>
  
  <?php
  require "footer.php";
  ?>
  
  <script src="./js/main.js"></script>
</body>
</html>