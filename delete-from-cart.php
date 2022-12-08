<?php
session_start();

$is_user_login = false;
$quantity_books = 0;

if(isset($_SESSION['user_id'])) {
  
  //Работаем с базой данных
  try {
    require_once ('db.php');
    
    $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
    $set_local->execute(); 
    $product_id = $_POST["product_id"];
    $user_id = $_SESSION['user_id'];
    $is_user_login = true;

    //Ищем в каком количестве был этот товар в корзине
    $select_quantity = $conn->prepare('SELECT quantity FROM shop_db.cart WHERE product_id=:product_id AND user_id=:user_id');
    $select_quantity->bindParam(':product_id', $product_id);
    $select_quantity->bindParam(':user_id', $user_id);
    $select_quantity->execute();    
    if($select_quantity->rowCount() > 0 ) {
      $quantity_books = $select_quantity->fetch(PDO::FETCH_COLUMN); 
    }  

    //Удаляем этот товар из корзины
    $delete = $conn->prepare('DELETE FROM shop_db.cart WHERE product_id=:product_id AND user_id=:user_id');
    $delete->bindParam(':product_id', $product_id);
    $delete->bindParam(':user_id', $user_id);
    $delete->execute();


  }
  catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
    echo 'ERROR: ' . $e->getMessage();
  }

} 
$arr['user'] = $is_user_login;
$arr['quantity'] = $quantity_books;
echo json_encode($arr);

?>