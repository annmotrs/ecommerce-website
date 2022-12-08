<?php
session_start();

//Работаем с базой данных
try {
    require_once ('db.php');
    
    $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); // подготовка запроса SQL на установку русской локации
    $set_local->execute(); // выполняем запрос

    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    $user_id = $_SESSION['user_id'];
    $quantity = $quantity - 1;

    //Если количество товара равно 0, удаляем товар, иначе обновляем значение количества 
    if($quantity === 0) {
      $delete = $conn->prepare('DELETE FROM shop_db.cart WHERE user_id=:user_id AND product_id=:product_id');
      $delete->bindParam(':user_id', $user_id);
      $delete->bindParam(':product_id', $product_id);
      $delete->execute();
    }
    else {
      $update = $conn->prepare('UPDATE shop_db.cart SET quantity = :quantity WHERE user_id=:user_id AND product_id=:product_id');
      $update->bindParam(':quantity', $quantity, PDO::PARAM_INT);
      $update->bindParam(':user_id', $user_id);
      $update->bindParam(':product_id', $product_id);
      $update->execute();
    }

}
catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
    echo 'ERROR: ' . $e->getMessage();
}

echo json_encode($quantity);

?>