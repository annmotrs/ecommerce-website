<?php
session_start();

//Работаем с базой данных
try {
    require_once ('db.php');
    
    $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); // подготовка запроса SQL на установку русской локации
    $set_local->execute(); // выполняем запрос

    $review_id = $_POST["review_id"];

    //Удаляем отзыв по его id
    $delete = $conn->prepare('DELETE FROM shop_db.reviews WHERE id=:id');
    $delete->bindParam(':id', $review_id);
    $delete->execute();

}
catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
    echo 'ERROR: ' . $e->getMessage();
}

?>