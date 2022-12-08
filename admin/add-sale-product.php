<?php
session_start();
$is_success = false;

//Работаем с базой данных
try {
    require_once ('../db.php');
    
    $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); // подготовка запроса SQL на установку русской локации
    $set_local->execute(); // выполняем запрос

    $product_id = $_POST["product_id"];
    //Обновляем товар, сделав его в продаже
    $update = $conn->prepare('UPDATE shop_db.products SET is_sale = 1 WHERE id=:id');
    $update->bindParam(':id', $product_id);
    $update->execute();
    $is_success = true;
}
catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
    echo 'ERROR: ' . $e->getMessage();
}

echo json_encode($is_success);

?>