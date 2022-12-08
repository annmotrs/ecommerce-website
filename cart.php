<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Корзина</title>
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

  <section class="cart">
    <div class="cart__wrapper">
      <h1 class="cart__title">Корзина</h1>
        <div class="cart__products">

        <?php
        if(isset($_SESSION['user_id'])) {

          try {
            require_once ('db.php');

            $user_id = $_SESSION['user_id'];
            $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
            $set_local->execute(); 

            //Делаем выборку всех товаров, которые находятся в корзине у авторизованного пользователя
            $select_books = $conn->prepare('SELECT products.title, products.price, products.photo, products.id, cart.quantity FROM shop_db.cart 
                                          JOIN shop_db.products ON shop_db.cart.product_id = shop_db.products.id 
                                          WHERE shop_db.cart.user_id=:user_id 
                                          ORDER BY shop_db.cart.date_added');
            $select_books ->bindParam(':user_id', $user_id);
            $select_books ->execute(); 

            if($select_books->rowCount() > 0 ) {
              $select_books_array = $select_books->fetchAll(); 
              $total_sum = 0;

              foreach($select_books_array as $select_books_array_row) { 

                //Считаем общую стоимость товаров в корзине
                $sum = $select_books_array_row['price'] * $select_books_array_row['quantity'];
                $total_sum += $sum;
        ?>

          <div class="cart__product" data-id="<?= $select_books_array_row['id']; ?>">
            <div class="cart__product-delete"><i class="fa-solid fa-square-xmark"></i></div>
            <div class="cart__product-image"><img src="./uploads/<?= $select_books_array_row['photo']; ?>" alt="<?= $select_books_array_row['title'] ?>"></div>
            <div class="cart__product-box cart__product-box1">
              <div class="cart__product-title"><a href="book.php?product_id=<?= $select_books_array_row['id']; ?>" class="cart__product-title-link"><?= $select_books_array_row['title'] ?></a></div>
              <p class="cart__product-price">Цена: <span class="cart__product-price-value"><?= $select_books_array_row['price'] ?></span> руб.</p>
            </div>
            <div class="cart__product-box cart__product-box2">
              <p class="cart__product-title-quantity">Количество</p>
              <div class="cart__box-book-to-cart book-to-cart">
                <div class="book-to-cart__buttons-update-quantity">
                  <div class="book-to-cart__minus"><i class="fa-solid fa-minus"></i></div>
                  <div class="book-to-cart__product-quantity"><?= $select_books_array_row['quantity']; ?></div>
                  <div class="book-to-cart__plus"><i class="fa-solid fa-plus"></i></div>
                </div>
              </div>
            </div>
            <div class="cart__product-box cart__product-box3">
              <p class="cart__product-title-sum">Сумма</p>
              <p class="cart__product-sum"><span class="cart__product-sum-value"><?= $sum ?></span> руб.</p>
            </div>           
          </div>

        <?php  
              }

              
        ?>
        </div>
        <div class="cart__final-sum"><span class="cart__final-sum-title">Общая стоимость: </span><span class="cart__final-sum-value"><?= $total_sum ?></span> руб.</div>
        <button type="button" class="button">Оформить заказ</button>
        <?php      
            } 
            else {
              echo '<h1 class="cart__message">Корзина пуста</h1>';
            } 
          }
          catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
                      echo 'ERROR: ' . $e->getMessage();
          }
        }
        else {
          echo '<h1 class="cart__message-login">Вы не авторизованы</h1><p class="cart__message-login-text">Для доступа к корзине необходимо <a class="cart__message-login-link" href="user-login.php">войти</a> или <a class="cart__message-login-link" href="user-register.php">зарегистрироваться</a></p>';
        }
        ?>



      </div>
  </section>
  
  <?php
  require "footer.php";
  ?>
  
  <script src="./js/main.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="./js/cart.js"></script>
</body>
</html>