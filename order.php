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
  <title>Оформление заказа</title>
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

  <section class="order">
    <div class="order__wrapper">
      <h1 class="order__title">Оформление заказа</h1>
      <div class="order__content">
        <div class="order__user-contacts">
          <h2 class="order__subtitle">Ваши данные</h2>
          <p class="order__warning">Если данные не соответствуют действительности, Вы можете поменять их в <a href="user-settings.php" class="order__warning-link">Личном кабинете</a>.</p>
          
          <?php

          try {
            require_once ('db.php');

            $id = $_SESSION['user_id'];
            $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
            $set_local->execute(); 

            //Делаем выборку записи с данными пользователя по его id
            $select_user = $conn->prepare('SELECT * FROM shop_db.users WHERE id=:id');
            $select_user ->bindParam(':id', $id);
            $select_user ->execute(); 

            //Если запись найдена, то выводим
            if($select_user->rowCount() > 0 ) {
              $select_user_array = $select_user->fetch(PDO::FETCH_ASSOC); 
            
          ?>
          
          <p class="order__user-property">Фамилия: <span class="order__user-value"><?= $select_user_array['surname']; ?></span></p>
          <p class="order__user-property">Имя: <span class="order__user-value"><?= $select_user_array['name']; ?></span></p>
          <p class="order__user-property">Отчество: <span class="order__user-value"><?= $select_user_array['patronymic']; ?></span></p>
          <p class="order__user-property">Город: <span class="order__user-value"><?= $select_user_array['city']; ?></span></p>
          <p class="order__user-property">e-mail: <span class="order__user-value"><?= $select_user_array['email']; ?></span></p>
          <p class="order__user-property">Телефон: <span class="order__user-value"><?= $select_user_array['phone']; ?></span></p> 

          <?php  
                }  
          ?>

        </div>
        <div class="order__products-info">
          <h2 class="order__subtitle">Ваш заказ</h2>
          <p class="order__warning">Если Вас не устраивает содержимое заказа, Вы можете поменять его в <a href="cart.php" class="order__warning-link">Корзине</a>.</p>
          <div class="order__products">

            <?php

              //Делаем выборку всех товаров в корзине у пользователя по его id
              $select_books = $conn->prepare('SELECT products.title, products.price, products.photo, products.id, cart.quantity FROM shop_db.cart 
                                              JOIN shop_db.products ON shop_db.cart.product_id = shop_db.products.id 
                                              WHERE shop_db.cart.user_id=:user_id 
                                              ORDER BY shop_db.cart.date_added');
              $select_books ->bindParam(':user_id', $id);
              $select_books ->execute(); 
              $total_sum = 0;

              if($select_books->rowCount() > 0 ) {
              $select_books_array = $select_books->fetchAll(); 
              
                //Выводим товары в цикле
                foreach($select_books_array as $select_books_array_row) { 

                $sum = $select_books_array_row['price'] * $select_books_array_row['quantity'];
                $total_sum += $sum;

            ?>

            <div class="order__product">
              <div class="order__product-image"><img src="./uploads/<?= $select_books_array_row['photo']; ?>" alt="<?= $select_books_array_row['title']; ?>"></div>
              <div class="order__product-box order__product-box1">
                <div class="order__product-title"><a href="book.php?product_id=<?= $select_books_array_row['id']; ?>" class="order__product-title-link"><?= $select_books_array_row['title']; ?></a></div>
                <p class="order__product-price">Цена: <?= $select_books_array_row['price']; ?> руб.</p>
              </div>
              <p class="order__product-quantity">x <span class="order__product-quantity-value"><?= $select_books_array_row['quantity']; ?></span></p>
              <div class="order__product-box order__product-box2">
                <p class="order__product-title-sum">Сумма</p>
                <p class="order__product-sum"><span class="order__product-sum-value"><?= $sum; ?></span> руб.</p>
              </div>  
            </div>

            <?php  
                }  
              }                
              else {
                  echo "Товары отсутствуют в корзине";
              }
            }  
            catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
                    echo 'ERROR: ' . $e->getMessage();
            }
            ?>

          </div>  

        </div>
      </div>
      <div class="order__final-sum"><span class="order__final-sum-title">Общая стоимость: </span><span class="order__final-sum-value"><?= $total_sum; ?></span> руб.</div>
      <?php 
      if($total_sum)
        echo '<button type="button" class="button">Заказать</button>';
      ?>
    </div>
  </section>
  
  <?php
  require "footer.php";
  ?>
  
  <script src="./js/main.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="./js/order.js"></script>
</body>
</html>