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

    //Удаляем этот товар из избранного
    $delete = $conn->prepare('DELETE FROM shop_db.wishlist WHERE product_id=:product_id AND user_id=:user_id');
    $delete->bindParam(':product_id', $product_id);
    $delete->bindParam(':user_id', $user_id);
    $delete->execute();

  }
  catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
    echo 'ERROR: ' . $e->getMessage();
  }

} 

echo json_encode($is_user_login);

?>