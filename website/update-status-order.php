<?php
session_start();

//Работаем с базой данных
try {
    require_once ('db.php');
    
    $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); // подготовка запроса SQL на установку русской локации
    $set_local->execute(); // выполняем запрос

    $order_id = $_POST["order_id"];
    $status = "отменен пользователем";

    //Обновляем статус заказа
    $update_status_order = $conn->prepare('UPDATE shop_db.orders SET status = :status WHERE id=:id');
    $update_status_order->bindParam(':id', $order_id);
    $update_status_order->bindParam(':status', $status);
    $update_status_order->execute();

}
catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
    echo 'ERROR: ' . $e->getMessage();
}

echo json_encode($status);

?>