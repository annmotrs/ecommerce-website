<?php
session_start();
$is_success = false;

//Работаем с базой данных
try {
    require_once ('../db.php');
    
    $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); // подготовка запроса SQL на установку русской локации
    $set_local->execute(); // выполняем запрос

    $product_id = $_POST["product_id"];
    //Обновляем товар, сделав его не в продаже
    $update = $conn->prepare('UPDATE shop_db.products SET is_sale = 0 WHERE id=:id');
    $update->bindParam(':id', $product_id);
    $update->execute();

    //Удаляем этот товар у всех из корзины
    $delete_cart = $conn->prepare('DELETE FROM shop_db.cart WHERE product_id=:product_id');
    $delete_cart->bindParam(':product_id', $product_id);
    $delete_cart->execute();    

    //Удаляем этот товар у всех из избранного
    $delete_wishlist = $conn->prepare('DELETE FROM shop_db.wishlist WHERE product_id=:product_id');
    $delete_wishlist->bindParam(':product_id', $product_id);
    $delete_wishlist->execute();   

    $is_success = true;
}
catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
    echo 'ERROR: ' . $e->getMessage();
}

echo json_encode($is_success);

?>