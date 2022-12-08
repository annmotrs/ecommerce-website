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
  <title>Мои отзывы</title>
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

  <section class="user-reviews">
    <div class="user-reviews__wrapper">
      <h1 class="user-reviews__title">Мои отзывы</h1>
        <a href="user-settings.php" class="user-reviews__link">Вернуться в личный кабинет</a>
        <div class="user-reviews__reviews">


        <?php

        try {
          require_once ('db.php');

          $user_id = $_SESSION['user_id'];
          $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
          $set_local->execute(); 

          //Делаем выборку всех отзывов написанных данным пользователем
          $select_user_reviews = $conn->prepare('SELECT products.photo, products.title, reviews.* FROM shop_db.reviews 
                                                JOIN shop_db.products ON shop_db.products.id = shop_db.reviews.product_id
                                                WHERE reviews.user_id=:user_id
                                                ORDER BY reviews.date_created DESC');
          $select_user_reviews ->bindParam(':user_id', $user_id);
          $select_user_reviews ->execute(); 

          if($select_user_reviews->rowCount() > 0 ) {
            $select_user_reviews_array = $select_user_reviews->fetchAll(); 
           
            foreach($select_user_reviews_array as $select_user_reviews_array_row) {
          
        ?>

          <div class="user-reviews__review">
            <div class="user-reviews__product">
              <div class="user-reviews__image"><img src="./uploads/<?= $select_user_reviews_array_row['photo']; ?>" alt="<?= $select_user_reviews_array_row['title'] ?>"></div>
              <div class="user-reviews__product-title"><a href="book.php?product_id=<?= $select_user_reviews_array_row['product_id'] ?>" class="user-reviews__title-link"><?= $select_user_reviews_array_row['title'] ?></a></div>              
            </div>
            <div class="user-reviews__info-status<?= $select_user_reviews_array_row['status'] === "отклонено" ? " user-reviews__info-status--fail" : ""?>"><?= $select_user_reviews_array_row['status']; ?></div>  
            <p class="user-reviews__property">Оценка:</p>
            <div class="user-reviews__grade">
              <?= str_repeat("★", $select_user_reviews_array_row['grade']); ?><span class="user-reviews__grade-null"><?= str_repeat("★", 5 - $select_user_reviews_array_row['grade']); ?></span>
            </div>  
            <p class="user-reviews__property">Отзыв:</p>
            <p class="user-reviews__text"><?= $select_user_reviews_array_row['description']; ?></p>
            <form action="update-review.php" method="POST" class="user-reviews__actions" data-id="<?= $select_user_reviews_array_row['id']; ?>">
              <input type="hidden" name="product-id" value="<?= $select_user_reviews_array_row['product_id']; ?>">
              <input type="hidden" name="title" value="<?= $select_user_reviews_array_row['title']; ?>">
              <input type="hidden" name="grade" value="<?= $select_user_reviews_array_row['grade']; ?>">
              <input type="hidden" name="description" value="<?= $select_user_reviews_array_row['description']; ?>">
              <button type="submit" class="user-reviews__button button" name="update-review">Редактировать</button>
              <button type="button" class="user-reviews__button button button-delete">Удалить</button>              
            </form>
          </div>

          <?php 

            }  
          } 
          else {
          echo '<h1 class="reviews__message">Отзывов нет</h1>';
          }
          } catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
          echo 'ERROR: ' . $e->getMessage();
          }    

          ?>

        </div>
    </div>
  </section>

  <dialog class="dialog">
    <p class="dialog__text">Вы точно хотите удалить отзыв? Отменить данное действие будет невозможно.</p>
    <form class="dialog__options" method="dialog">
      <button class="dialog__button dialog__button-cancel" value="no">Отмена</button>
      <button class="dialog__button dialog__button-delete" value="yes">Удалить</button>
    </form>
  </dialog>
  
  <?php
  require "footer.php";
  ?>
  
  <script src="./js/main.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="./js/deleteReview.js"></script>
  
</body>
</html>