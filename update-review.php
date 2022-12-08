<?php
session_start();

if(!isset($_SESSION['user_id'])) {
  header("Location: user-login.php");
}    

if (isset($_POST['update-review'])){
  $product_id = $_POST['product-id'];
  $title = $_POST['title'];
  $description = $_POST['description'];
  $grade = (int)$_POST['grade'];
  
}  
else {
  header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Редактирование отзыва</title>
  <link rel="icon" type="image/x-icon" href="./images/icon.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/media.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
</head>
<body>

  <?php
  require "header.php";
  ?>

  <section class="create-review">
    <div class="create-review__wrapper">
      <h1 class="create-review__title">Редактирование отзыва на <a class="create-review__title-link" href="book.php?product_id=<?= $product_id ?>"><?= $title ?></a></h1>
      <form class="create-review__form" action="for-update-review.php" method="POST">
        <label  class="create-review__label">Оценка</label>
        <div class="create-review__grade grade">
          <div class="grade__items">
            <input type="radio" name="grade" id="grade__5" class="grade__item" value="5" required<?= $grade === 5 ? ' checked' : '' ?>>
            <label for="grade__5" class="grade__label"></label>
            <input type="radio" name="grade" id="grade__4" class="grade__item" value="4"<?= $grade === 4 ? ' checked' : '' ?>>
            <label for="grade__4" class="grade__label"></label>
            <input type="radio" name="grade" id="grade__3" class="grade__item" value="3"<?= $grade === 3 ? ' checked' : '' ?>>
            <label for="grade__3" class="grade__label"></label>
            <input type="radio" name="grade" id="grade__2" class="grade__item" value="2"<?= $grade === 2 ? ' checked' : '' ?>>
            <label for="grade__2" class="grade__label"></label>
            <input type="radio" name="grade" id="grade__1" class="grade__item" value="1"<?= $grade === 1 ? ' checked' : '' ?>>
            <label for="grade__1" class="grade__label"></label>
          </div>
        </div>
        <label  class="create-review__label" for="review">Отзыв</label>
        <textarea class="create-review__textarea" name="review" id="review" rows="10" required><?= $description ?></textarea>
        <input type="hidden" name="product-id" value="<?= $product_id ?>">
        <button type="submit" class="button" name="update-review">Отправить</button>
      </form>
    </div>
  </section>
  
  <?php
  require "footer.php";
  ?>
  
  <script src="./js/main.js"></script>
</body>
</html>