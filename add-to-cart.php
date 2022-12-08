<?php
session_start();

$is_user_login = false;

if(isset($_SESSION['user_id'])) {
  
  //Работаем с базой данных
  try {
    require_once ('db.php');
    
    $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
    $set_local->execute(); 
    $product_id = $_POST["product_id"];
    $user_id = $_SESSION['user_id'];
    $is_user_login = true;

    //Делаем выборку количества записей, чтобы проверить есть ли там уже добавляемый товар у этого пользователя
    $select_cart = $conn->prepare('SELECT COUNT(*) FROM shop_db.cart WHERE product_id=:product_id AND user_id=:user_id');
    $select_cart ->bindParam(':product_id', $product_id);
    $select_cart ->bindParam(':user_id', $user_id);
    $select_cart ->execute(); 
    $num_rows_cart = $select_cart->fetchColumn();    

    //Если товара нет, то добавляем в корзину
    if($num_rows_cart === 0 ) {
      $stmt = $conn->prepare('INSERT INTO shop_db.cart (id, user_id, product_id, quantity, date_added) VALUES (NULL, :user_id, :product_id, 1, NOW())');
      $stmt->bindParam(':product_id', $product_id);
      $stmt->bindParam(':user_id', $user_id);
      $stmt->execute();
    } 
    

  }
  catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
    echo 'ERROR: ' . $e->getMessage();
  }

} 

echo json_encode($is_user_login);

?>