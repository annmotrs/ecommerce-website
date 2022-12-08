<?php
session_start();
$is_success = false;

//Работаем с базой данных
try {
    require_once ('../db.php');
    
    $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); // подготовка запроса SQL на установку русской локации
    $set_local->execute(); // выполняем запрос

    $order_id = $_POST["order_id"];
    $status = "принято в работу";
    //Обновляем статус заказа на "принято в работу"
    $update = $conn->prepare('UPDATE shop_db.orders SET status = :status WHERE id=:id');
    $update->bindParam(':status', $status);
    $update->bindParam(':id', $order_id);
    $update->execute();
    $is_success = true;
}
catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
    echo 'ERROR: ' . $e->getMessage();
}

echo json_encode($is_success);

?>