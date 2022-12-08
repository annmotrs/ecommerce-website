<?php
session_start();

if(isset($_SESSION['user_id'])) {
  
  //Работаем с базой данных
  try {
    require_once ('db.php');
    
    $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
    $set_local->execute(); 
    $user_id = $_SESSION['user_id'];

    //Считаем общую стоимость заказа
    $select_cart_sum = $conn->prepare('SELECT SUM(cart.quantity * products.price) FROM shop_db.cart 
                                        JOIN shop_db.products ON shop_db.cart.product_id = shop_db.products.id  
                                        WHERE shop_db.cart.user_id=:user_id');
    $select_cart_sum ->bindParam(':user_id', $user_id);
    $select_cart_sum ->execute(); 
    $total_sum = $select_cart_sum->fetchColumn();   

    //Добавляем в БД запись с данными заказа
    $status = "в обработке";
    $insert_orders = $conn->prepare('INSERT INTO shop_db.orders (id, user_id, total_sum, status, date_order) VALUES (NULL, :user_id, :total_sum, :status, NOW())');
    $insert_orders->bindParam(':user_id', $user_id);
    $insert_orders->bindParam(':total_sum', $total_sum);
    $insert_orders->bindParam(':status', $status);
    $insert_orders->execute();
    $last_order_id = $conn->lastInsertId();

    //Выборка всех товаров в корзине
    $select_cart = $conn->prepare('SELECT products.id, cart.quantity, products.price FROM shop_db.cart 
                                        JOIN shop_db.products ON shop_db.cart.product_id = shop_db.products.id  
                                        WHERE shop_db.cart.user_id=:user_id
                                        ORDER BY shop_db.cart.date_added');
    $select_cart ->bindParam(':user_id', $user_id);
    $select_cart ->execute(); 
    $select_cart_array = $select_cart->fetchAll();   

    //Добавляем в БД в таблицу orders_products товары, которые в данном заказе 
    foreach($select_cart_array as $select_cart_array_row) { 
      $sum = $select_cart_array_row['quantity'] * $select_cart_array_row['price'];
      $insert_orders_products = $conn->prepare('INSERT INTO shop_db.orders_products (id, order_id, product_id, unit_price, quantity, sum) VALUES (NULL, :order_id, :product_id, :unit_price, :quantity, :sum)');
      $insert_orders_products->bindParam(':order_id', $last_order_id);
      $insert_orders_products->bindParam(':product_id', $select_cart_array_row['id']);
      $insert_orders_products->bindParam(':unit_price', $select_cart_array_row['price']);
      $insert_orders_products->bindParam(':quantity', $select_cart_array_row['quantity']);
      $insert_orders_products->bindParam(':sum', $sum);
      $insert_orders_products->execute();
    }
    
    //Удаляем всё из корзины пользователя, который сделал заказ
    $delete_from_cart = $conn->prepare('DELETE FROM shop_db.cart WHERE user_id=:user_id');
    $delete_from_cart->bindParam(':user_id', $user_id);
    $delete_from_cart->execute();

  }
  catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
    echo 'ERROR: ' . $e->getMessage();
  }

} 

echo json_encode($last_order_id);

?>