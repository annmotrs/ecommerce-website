<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Избранное</title>
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

  <section class="wishlist">
    <div class="wishlist__wrapper">
      <h1 class="wishlist__title">Избранное</h1>
        <div class="wishlist__products">

        <?php
        if(isset($_SESSION['user_id'])) {

          try {
            require_once ('db.php');

            $user_id = $_SESSION['user_id'];
            $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
            $set_local->execute(); 

            //Делаем выборку всех товаров, которые в избранном у данного пользователя
            $select_books = $conn->prepare('SELECT products.title, products.price, products.photo, products.id FROM shop_db.wishlist 
                                          JOIN shop_db.products ON shop_db.wishlist.product_id = shop_db.products.id 
                                          WHERE shop_db.wishlist.user_id=:user_id 
                                          ORDER BY shop_db.wishlist.date_added');
            $select_books ->bindParam(':user_id', $user_id);
            $select_books ->execute(); 

            if($select_books->rowCount() > 0 ) {
              $select_books_array = $select_books->fetchAll(); 

              //Делаем выборку всех товаров, которые в корзине у данного пользователя
              $select_books_cart_array = [];
              $select_books_cart = $conn->prepare('SELECT product_id FROM shop_db.cart WHERE user_id=:user_id');
              $select_books_cart ->bindParam(':user_id', $user_id);
              $select_books_cart ->execute();
              $select_books_cart_array = $select_books_cart->fetchAll(PDO::FETCH_COLUMN); 

              //Выводим товары в цикле
              foreach($select_books_array as $select_books_array_row) { 
          
        ?>

          <div class="wishlist__product" data-id="<?= $select_books_array_row['id']; ?>">
            <div class="wishlist__product-delete"><i class="fa-solid fa-square-xmark"></i></div>
            <div class="wishlist__product-image"><img src="./uploads/<?= $select_books_array_row['photo']; ?>" alt="<?= $select_books_array_row['title'] ?>"></div>
            <div class="wishlist__product-title"><a href="book.php?product_id=<?= $select_books_array_row['id']; ?>" class="wishlist__product-title-link"><?= $select_books_array_row['title'] ?></a></div>
            <p class="wishlist__product-price"><?= $select_books_array_row['price'] ?> руб.</p>
            <button type="button" class="wishlist__button button"><span class="wishlist__button-text"><?= in_array($select_books_array_row['id'], $select_books_cart_array) ? "Убрать из " : "Поместить в "; ?></span><i class="fa fa-shopping-cart header__user-navigation-icon"></i></button>
          </div>

        <?php  
              }
            } 
            else {
              echo '<h1 class="wishlist__message">В избранном пусто</h1>';
            } 
          }
          catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
                      echo 'ERROR: ' . $e->getMessage();
          }
        }
        else {
          echo '<h1 class="wishlist__message-login">Вы не авторизованы</h1><p class="wishlist__message-login-text">Для доступа к избранному необходимо <a class="wishlist__message-login-link" href="user-login.php">войти</a> или <a class="wishlist__message-login-link" href="user-register.php">зарегистрироваться</a></p>';
        }
        ?>
          
        </div>
    </div>
  </section>
  
  <?php
  require "footer.php";
  ?>
  
  <script src="./js/main.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="./js/wishlist.js"></script>
</body>
</html>