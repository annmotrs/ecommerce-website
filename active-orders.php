<?php
session_start();
//Если пользователь не авторизовался, то перенаправить на страницу, где надо авторизоваться
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
  <title>Активные заказы</title>
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

  <section class="orders">
    <div class="orders__wrapper">
      <h1 class="orders__title">Активные заказы</h1>
        <a href="user-settings.php" class="orders__link">Вернуться в личный кабинет</a>
        <div class="orders__orders">

        <?php
          try {
            require_once ('db.php');

            $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
            $set_local->execute(); 
            $user_id = $_SESSION['user_id'];

            //Выборка всех заказов со статусом "в обработке", "принято в работу", "доставляется", "в пункте выдачи" авторизованного пользователя по его id
            $select_orders = $conn->prepare('SELECT * FROM shop_db.orders WHERE user_id=:user_id AND status IN ("в обработке", "принято в работу", "доставляется", "в пункте выдачи") ORDER BY date_order DESC');
            $select_orders ->bindParam(':user_id', $user_id);
            $select_orders ->execute(); 

            //Если заказы найдены, выводим их
            if($select_orders->rowCount() > 0) {
              $select_orders_array = $select_orders->fetchAll();                

              //Выводим данные заказа в цикле
              foreach($select_orders_array as $select_orders_array_row) { 
                $date_order = new DateTime($select_orders_array_row['date_order']);

        ?>

          <div class="orders__order">
            <div class="orders__main-content">
              <div class="orders__info">
                <div class="orders__info-box">
                  <h2 class="orders__info-title">Заказ №<?= $select_orders_array_row['id']; ?> от <?= $date_order->Format('d.m.Y'); ?></h2>
                  <p class="orders__info-sum">Стоимость: <span class="orders__info-sum-value"><?= $select_orders_array_row['total_sum']; ?> руб.</span></p>                     
                </div>
                <div class="orders__info-box">   
                  <div class="orders__info-status<?= $select_orders_array_row['status'] === "отменен интернет-магазином" || $select_orders_array_row['status'] === "отменен пользователем" ? ' orders__info-status--fail' : ''; ?>"><?= $select_orders_array_row['status']; ?></div>  
                </div>    
              </div>
              <div class="orders__actions" data-id="<?= $select_orders_array_row['id']; ?>">
                <button type="button" class="orders__button-products">Показать товары</button>
                <?php
                if($select_orders_array_row['status'] === "в обработке") {
                  echo '<button type="button" class="button">Отменить заказ</button>';
                }
                ?>
              </div>
            </div>
            <div class="orders__products">

              <?php

                //Выборка всех товаров в данном заказе
                $select_orders_products = $conn->prepare('SELECT * FROM shop_db.orders_products 
                                                  JOIN shop_db.products ON shop_db.orders_products.product_id = shop_db.products.id
                                                  WHERE order_id=:order_id 
                                                  ORDER BY shop_db.orders_products.id');
                $select_orders_products ->bindParam(':order_id', $select_orders_array_row['id']);
                $select_orders_products ->execute(); 

                //Если товары найдены, то выводим их
                if($select_orders_products->rowCount() > 0) {
                  $select_orders_products_array = $select_orders_products->fetchAll();                

                  //Выводим массив товаров в цикле
                  foreach($select_orders_products_array as $select_orders_products_array_row) { 

              ?>

              <div class="orders__product">
                <div class="orders__product-image"><img src="./uploads/<?= $select_orders_products_array_row['photo']; ?>" alt="<?= $select_orders_products_array_row['title']; ?>"></div>
                <div class="orders__product-box orders__product-box1">
                  <div class="orders__product-title"><a href="book.php?product_id=<?= $select_orders_products_array_row['product_id']; ?>" class="orders__product-title-link"><?= $select_orders_products_array_row['title']; ?></a></div>
                  <p class="orders__product-price">Цена: <?= $select_orders_products_array_row['unit_price']; ?> руб.</p>
                </div>
                <p class="orders__product-quantity">x <span class="orders__product-quantity-value"><?= $select_orders_products_array_row['quantity']; ?></span></p>
                <div class="orders__product-box orders__product-box2">
                  <p class="orders__product-title-sum">Сумма</p>
                  <p class="orders__product-sum"><span class="orders__product-sum-value"><?= $select_orders_products_array_row['sum']; ?></span> руб.</p>
                </div>  
              </div>
                <?php

                  }
                }

                ?>

              

            </div>
          </div>

          <?php      
              }
            } 
            else {
              echo '<h1 class="orders__message">Активных заказов нет</h1>';
            } 
          }
          catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
            echo 'ERROR: ' . $e->getMessage();
          }
          ?>

        </div>
    </div>
  </section>

  <!-- Модальное окно, которое отображается при нажатии на кнопку "Отменить заказ" -->
  <dialog class="dialog">
    <p class="dialog__text">Вы точно хотите отменить заказ? Отменить данное действие будет невозможно.</p>
    <form class="dialog__options" method="dialog">
      <button class="dialog__button dialog__button-cancel" value="no">Отмена</button>
      <button class="dialog__button dialog__button-delete" value="yes">Отменить заказ</button>
    </form>
  </dialog>
  
  <?php
  require "footer.php";
  ?>
  
  <script src="./js/main.js"></script>
  <script src="./js/viewProducts.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="./js/cancelOrder.js"></script>
</body>
</html>