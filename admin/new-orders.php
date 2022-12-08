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
  <title>Поступившие заказы</title>
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

  <section class="orders container">
    <h1 class="title">Поступившие заказы</h1>
    <?php 
          try {
            require_once ('../db.php');

            $admin_id = $_SESSION['admin_id'];
            $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
            $set_local->execute(); 
    
            //Выборка всех заказов со статусом "в обработке"
            $select_orders = $conn->prepare('SELECT *, orders.id AS order_id FROM shop_db.orders 
                                              JOIN shop_db.users ON shop_db.orders.user_id = shop_db.users.id 
                                              WHERE shop_db.orders.status = "в обработке"
                                              ORDER BY shop_db.orders.date_order DESC');
            $select_orders ->execute(); 
    
            if($select_orders->rowCount() > 0 ) {
              $select_orders_array = $select_orders->fetchAll(); 

              echo '<div class="orders__cards">';

              //Выводим все заказы
              foreach($select_orders_array as $select_orders_array_row) {
                $date_order = new DateTime($select_orders_array_row['date_order']);
                $date_order = $date_order->Format('d.m.Y, H:i');
                
      ?>

      <div class="orders__card">
        <p class="orders__card-date"><?= $date_order; ?></p>
        <p class="orders__card-title">Данные пользователя</p>
        <p class="orders__card-property">ID пользователя: <span class="orders__card-value"><?= $select_orders_array_row['user_id']; ?></span></p>
        <p class="orders__card-property">Фамилия: <span class="orders__card-value"><?= $select_orders_array_row['surname']; ?></span></p>
        <p class="orders__card-property">Имя: <span class="orders__card-value"><?= $select_orders_array_row['name']; ?></span></p>
        <p class="orders__card-property">Отчество: <span class="orders__card-value"><?= $select_orders_array_row['patronymic']; ?></span></p>
        <p class="orders__card-property">Город: <span class="orders__card-value"><?= $select_orders_array_row['city']; ?></span></p>
        <p class="orders__card-property">email: <span class="orders__card-value"><?= $select_orders_array_row['email']; ?></span></p>
        <p class="orders__card-property">Телефон: <span class="orders__card-value"><?= $select_orders_array_row['phone']; ?></span></p>
        <p class="orders__card-title">Данные заказа</p>
        <p class="orders__card-property">ID заказа: <span class="orders__card-value"><?= $select_orders_array_row['order_id']; ?></span></p>
        <p class="orders__card-property">Стоимость заказа: <span class="orders__card-value"><?= $select_orders_array_row['total_sum']; ?> руб.</span></p>
        <p class="orders__card-property">Товары в заказе:</p>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Название</th>
              <th>Цена</th>
              <th>Кол-во</th>
              <th>Сумма</th>
            </tr>       
          </thead>
          <tbody> 
            
      <?php
      
        //Выборка всех товаров в данном заказе  
        $select_products = $conn->prepare('SELECT orders_products.*, products.title FROM shop_db.orders_products
                                          JOIN shop_db.products ON shop_db.orders_products.product_id = shop_db.products.id 
                                          WHERE order_id = :order_id');
        $select_products ->bindParam(':order_id', $select_orders_array_row['order_id']);                                  
        $select_products ->execute(); 

        if($select_products->rowCount() > 0 ) {
          $select_products_array = $select_products->fetchAll();

          //Выводим товары
          foreach($select_products_array as $select_products_array_row) {

      ?>

            <tr>
              <td><?= $select_products_array_row['product_id']; ?></td>
              <td><?= $select_products_array_row['title']; ?></td>
              <td><?= $select_products_array_row['unit_price']; ?></td>
              <td><?= $select_products_array_row['quantity']; ?></td>
              <td><?= $select_products_array_row['sum']; ?></td>
            </tr>

      <?php

          }
        }
      
      ?>

          </tbody>  
        </table>
        <div class="orders__action action" data-id="<?= $select_orders_array_row['order_id']; ?>">
          <button class="button orders__button-taken">Принято в работу</button>
          <button class="button orders__button-cancel">Отклонить</button>
        </div>
      </div>

      <?php 
            }
            echo '</div>';
          }
          else {
            echo '<div class="message">Заказов нет</div>';
          }  
        }
        catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
          echo 'ERROR: ' . $e->getMessage();
        }

      ?>

  </section>

  
  <script src="../js/main.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="../js/orderCancel.js"></script>
  <script src="../js/orderInProgress.js"></script>

</body>
</html>