<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Отзывы</title>
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

  <section class="reviews">
    <div class="reviews__wrapper">

      <?php 
        if(isset($_GET['product_id'])){
          $product_id = $_GET['product_id'];
        

          try {
            require_once ('db.php');
    
            $product_id = $_GET['product_id'];
            $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
            $set_local->execute(); 
            
            //Делаем выборку, чтоб узнать название книги
            $select_book = $conn->prepare('SELECT * FROM shop_db.products WHERE shop_db.products.id=:id');
            $select_book ->bindParam(':id', $product_id);
            $select_book ->execute(); 
    
            if($select_book->rowCount() > 0 ) {
              $select_book_array = $select_book->fetch(PDO::FETCH_ASSOC); 
      ?>

      <h1 class="reviews__title">Отзывы на <a class="reviews__title-link" href="book.php?product_id=<?= $select_book_array['id']; ?>"><?= $select_book_array['title']; ?></a></h1>

      <?php

            }



      ?>

      <div class="reviews__reviews">

        <?php 

        //Делаем выборку всех опубликованных отзывов на данный товар
        $select_reviews = $conn->prepare('SELECT users.name, users.surname, reviews.* FROM shop_db.reviews 
                                          JOIN shop_db.users ON shop_db.reviews.user_id = shop_db.users.id 
                                          WHERE shop_db.reviews.product_id=:product_id AND shop_db.reviews.status = "опубликовано"
                                          ORDER BY shop_db.reviews.date_created DESC');
        $select_reviews ->bindParam(':product_id', $product_id);
        $select_reviews ->execute(); 

        if($select_reviews->rowCount() > 0 ) {
          $select_reviews_array = $select_reviews->fetchAll(); 

          foreach($select_reviews_array as $select_reviews_array_row) {
            $date_created = new DateTime($select_reviews_array_row['date_created']);
            $date_updated = new DateTime($select_reviews_array_row['date_updated']);
            $date_updated = $date_updated->Format('d.m.Y');
        ?>

        <div class="reviews__review">
          <div class="reviews__info">
            <div class="reviews__row">
              <i class="fa-solid fa-circle-user reviews__icon"></i>
              <div class="reviews__column">
                <p class="reviews__name"><?= $select_reviews_array_row['surname']; ?> <?= $select_reviews_array_row['name']; ?></p>
                <p class="reviews__date"><?= $date_created->Format('d.m.Y'); ?> <?= $select_reviews_array_row['date_updated'] ? "(изм. $date_updated)" : ""; ?> </p>                    
              </div>
            </div>
            <div class="reviews__grade">
              <?= str_repeat("★", $select_reviews_array_row['grade']); ?><span class="reviews__grade-null"><?= str_repeat("★", 5 - $select_reviews_array_row['grade']); ?></span>
            </div>            
          </div>
          <p class="reviews__text"><?= $select_reviews_array_row['description']; ?></p>
        </div>

        <?php

          }  
        } else {
          echo '<h1 class="reviews__message">Отзывов нет</h1>';
        }
        ?>

      </div>

      <?php
          } catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
            echo 'ERROR: ' . $e->getMessage();
          }  
        }
      ?>

    </div>
  </section>
  
  <?php
  require "footer.php";
  ?>
  
  <script src="./js/main.js"></script>
</body>
</html>