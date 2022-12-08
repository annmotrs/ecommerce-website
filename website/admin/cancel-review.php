<?php
session_start();
$is_success = false;

//Работаем с базой данных
try {
    require_once ('../db.php');
    
    $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); // подготовка запроса SQL на установку русской локации
    $set_local->execute(); // выполняем запрос

    $review_id = $_POST["review_id"];
    $status = "отклонено";
    //Обновляем статус отзыва на "отклонено"
    $update = $conn->prepare('UPDATE shop_db.reviews SET status = :status WHERE id=:id');
    $update->bindParam(':status', $status);
    $update->bindParam(':id', $review_id);
    $update->execute();
    $is_success = true;
}
catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
    echo 'ERROR: ' . $e->getMessage();
}

echo json_encode($is_success);

?>