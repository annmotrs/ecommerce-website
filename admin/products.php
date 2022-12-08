<?php
session_start();

if(!isset($_SESSION['admin_id'])) {
  header("Location: index.php");
}    
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Товары</title>
  <link rel="icon" type="image/x-icon" href="../images/icon.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link rel="stylesheet" href="../css/admin-style.css">
  <link rel="stylesheet" href="../css/admin-media.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
</head>
<body>

  <?php
    require "admin-header.php";
  ?>

  <section class="products container">
    <h1 class="title">Товары</h1>
    <div class="products__cards">

    <?php 
          try {
            require_once ('../db.php');

            $admin_id = $_SESSION['admin_id'];
            $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
            $set_local->execute(); 
            
            //Выборка всех товаров
            $select_products = $conn->prepare('SELECT products.*, disciplines.name AS discipline_name, publishers.name AS publisher_name, bindings.name AS binding_name, categories.name AS category_name FROM shop_db.products 
                                              JOIN shop_db.disciplines ON shop_db.products.discipline_id = shop_db.disciplines.id 
                                              JOIN shop_db.publishers ON shop_db.products.publisher_id = shop_db.publishers.id 
                                              JOIN shop_db.bindings ON shop_db.products.binding_id = shop_db.bindings.id 
                                              JOIN shop_db.categories ON shop_db.products.category_id = shop_db.categories.id 
                                              ORDER BY shop_db.products.publication_date DESC');
            $select_products ->execute(); 
    
            if($select_products->rowCount() > 0 ) {
              $select_products_array = $select_products->fetchAll(); 

              //Выводим товары в цикле
              foreach($select_products_array as $select_products_array_row) {

                //Выборка всех авторов данной книги
                $select_products_authors = $conn->prepare('SELECT authors.name FROM shop_db.products_authors 
                                                        JOIN shop_db.authors ON shop_db.products_authors.author_id = shop_db.authors.id 
                                                        WHERE shop_db.products_authors.product_id=:id');
                $select_products_authors ->bindParam(':id', $select_products_array_row['id']);
                $select_products_authors ->execute(); 
                $select_products_authors_array = $select_products_authors->fetchAll(PDO::FETCH_COLUMN); 

                $publication_date = new DateTime($select_products_array_row['publication_date']);
                $publication_date = $publication_date->Format('d.m.Y, H:i');

      ?>

      <div class="products__card">
        <div class="products__card-row">
          <div class="products__card-image">
            <img src="../uploads/<?= $select_products_array_row['photo']; ?>" alt="<?= $select_products_array_row['title']; ?>">
          </div>
          <div class="products__card-info">
            <p class="products__card-property">ID товара: <span class="products__card-value"><?= $select_products_array_row['id']; ?></span></p>
            <p class="products__card-property">Название: <span class="products__card-value"><?= $select_products_array_row['title']; ?></span></p>
            <p class="products__card-property">Авторы: <span class="products__card-value"><?= implode(", ", $select_products_authors_array); ?></span></p>
            <p class="products__card-property">Категория: <span class="products__card-value"><?= $select_products_array_row['category_name']; ?></span></p>
            <p class="products__card-property">Предмет: <span class="products__card-value"><?= $select_products_array_row['discipline_name']; ?></span></p>
            <p class="products__card-property">Издательство: <span class="products__card-value"><?= $select_products_array_row['publisher_name']; ?></span></p>
            <p class="products__card-property">Количество страниц: <span class="products__card-value"><?= $select_products_array_row['num_pages']; ?></span></p>
            <p class="products__card-property">Год выпуска: <span class="products__card-value"><?= $select_products_array_row['year_of_publication']; ?></span></p>
            <p class="products__card-property">Переплет: <span class="products__card-value"><?= $select_products_array_row['binding_name']; ?></span></p>
          </div>               
        </div>
        <div class="products__card-row">
          <div class="products__card-description">
            <div class="products__card-description-title">Описание:</div> 
            <div class="products__card-description-text"><?= $select_products_array_row['description']; ?></div>
          </div>
        </div>
        <p class="products__card-property">Дата добавления: <span class="products__card-value"><?= $publication_date ?></span></p>
        <p class="products__card-property">Цена: <span class="products__card-price-value"><?= $select_products_array_row['price']; ?> руб.</span></p>
        <p class="products__card-property">Cтатус: <span class="products__card-value products__card-status-value<?= $select_products_array_row['is_sale'] === 1 ? ' products__card-value--color_green' : ' products__card-value--color_red' ?>"><?= $select_products_array_row['is_sale'] === 1 ? 'в продаже' : 'не в продаже' ?></span></p>
        <div class="products__action action" data-id="<?= $select_products_array_row['id']; ?>">
          <form action="update-product.php" method="POST">
            <input type="hidden" name="product_id" value="<?= $select_products_array_row['id']; ?>">
            <button class="button products__button-update-product" type="submit"><i class="fa-solid fa-pen-to-square"></i></button>
          </form>
          <button class="button products__button-update-sale<?= $select_products_array_row['is_sale'] === 1 ? ' products__button-delete-sale' : ' products__button-add-sale' ?>"><?= $select_products_array_row['is_sale'] === 1 ? 'Убрать из продажи' : 'Добавить в продажу' ?></button>
        </div>
      </div>

      <?php 
            }
          }  
        }
        catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
          echo 'ERROR: ' . $e->getMessage();
        }

      ?>

    </div>
  </section>

  
  <script src="../js/main.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="../js/updateSale.js"></script>

</body>
</html>