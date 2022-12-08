<?php
session_start();

$error = false;
function text_error() {
  echo('<h1 class="books__message">Товаров нет!</h1>');
}  
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Книги</title>
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

  <section class="books">
    <div class="container">
      <div class="books__wrapper">

      <?php
      if(!empty($_GET['category_id'])){

        try {
          require_once ('db.php');

          $category_id = $_GET['category_id'];
          $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
          $set_local->execute(); 

          //Делам выборку записи с данной категорией по ее id
          $select_book_category = $conn->prepare('SELECT * FROM shop_db.categories WHERE id=:id');
          $select_book_category ->bindParam(':id', $category_id);
          $select_book_category ->execute(); 
          $select_book_category_array = $select_book_category->fetch(PDO::FETCH_ASSOC); 

      ?>
      
        <h1 class="books__title title"><?= isset($select_book_category_array['name']) ? $select_book_category_array['name'] : "Такой категории нет"; ?></h1>
       
        <?php

          //Делам выборку всех товаров, которые в продаже, в данной категории
          $select_books = $conn->prepare('SELECT products.*, IFNULL(SUM(quantity), 0) AS "sold_books" FROM shop_db.products
                                          LEFT JOIN shop_db.orders_products ON products.id = orders_products.product_id 
                                          WHERE category_id=:category_id AND is_sale = 1
                                          GROUP BY products.id 
                                          ORDER BY SUM(quantity) DESC');
          $select_books ->bindParam(':category_id', $category_id);
          $select_books ->execute(); 

          if($select_books->rowCount() > 0) {

          ?>

        <select id="select-sort">
          <option value="popular">Популярные</option>
          <option value="new">Новинки</option>
          <option value="price-min">Сначала дешевые</option>
          <option value="price-max">Сначала дорогие</option>
        </select>          

        <?php          
            }  
        ?>
       
        <div class="books__cards">

        <?php
            //Если товары найдены, то выводим их
            if($select_books->rowCount() > 0) {
              $select_books_array = $select_books->fetchAll(); 

              $select_books_wishlist_array = [];
              $select_books_cart_array = [];
              if(isset($_SESSION['user_id'])){
                $user_id = $_SESSION['user_id'];
                //Находим все товары, которые находятся у пользователя в избранном, чтоб потом задать кнопкам нужное состояние           
                $select_books_wishlist = $conn->prepare('SELECT product_id FROM shop_db.wishlist WHERE user_id=:user_id');
                $select_books_wishlist ->bindParam(':user_id', $user_id);
                $select_books_wishlist ->execute();
                $select_books_wishlist_array = $select_books_wishlist->fetchAll(PDO::FETCH_COLUMN); 
                //Находим все товары, которые находятся у пользователя в корзине, и их количество чтоб потом задать кнопкам нужное состояние  
                $select_books_cart = $conn->prepare('SELECT product_id, quantity FROM shop_db.cart WHERE user_id=:user_id');
                $select_books_cart ->bindParam(':user_id', $user_id);
                $select_books_cart ->execute();
                $select_books_cart_array = $select_books_cart->fetchAll(PDO::FETCH_KEY_PAIR); 
              }                 

              //Выводим товары в цикле
              foreach($select_books_array as $select_books_array_row) { 
   
                //Делаем выборку всех авторов данной книги
                $select_book_authors = $conn->prepare('SELECT authors.name FROM shop_db.products_authors 
                                                        JOIN shop_db.authors ON shop_db.products_authors.author_id = shop_db.authors.id 
                                                        WHERE shop_db.products_authors.product_id=:id');
                $select_book_authors ->bindParam(':id', $select_books_array_row['id']);
                $select_book_authors ->execute(); 
                $select_book_authors_array = $select_book_authors->fetchAll(PDO::FETCH_COLUMN); 

                
        ?>

          <div class="books__card" data-price="<?= $select_books_array_row['price']; ?>" data-id="<?= $select_books_array_row['id']; ?>" data-quantity="<?= $select_books_array_row['sold_books']; ?>">
            <div class="books__card-image"><a href="book.php?product_id=<?= $select_books_array_row['id']; ?>"><img src="./uploads/<?= $select_books_array_row['photo']; ?>" alt="<?= $select_books_array_row['title']; ?>"></a></div>
            <p class="books__card-title"><a href="book.php?product_id=<?= $select_books_array_row['id']; ?>" class="books__card-link"><?= $select_books_array_row['title']; ?></a></p>
            <p class="books__card-author"><?= implode(", ", $select_book_authors_array); ?></p>
            <p class="books__card-price"><?= $select_books_array_row['price']; ?> руб.</p>
            <div class="books__action action" data-id="<?= $select_books_array_row['id']; ?>">
              <div class="books__wrapper-action">
                <button type="button" class="books__button button"><?= array_key_exists($select_books_array_row['id'], $select_books_cart_array) ? "В корзине" : "В корзину" ?></button>
                <?php if(array_key_exists($select_books_array_row['id'], $select_books_cart_array)) {
                          $id = $select_books_array_row['id'];
                          echo "<div class='books__box-book-to-cart book-to-cart'>  
                                  <div class='book-to-cart__buttons-update-quantity'>
                                    <div class='book-to-cart__minus'><i class='fa-solid fa-minus'></i></div>
                                    <div class='book-to-cart__product-quantity'>$select_books_cart_array[$id]</div>
                                    <div class='book-to-cart__plus'><i class='fa-solid fa-plus'></i></div>
                                  </div>
                                </div>";  
                        }
                ?>
                <div class="books__icon-wishlist icon-wishlist<?= in_array($select_books_array_row['id'], $select_books_wishlist_array) ? ' icon-wishlist--active' : ''; ?>"><i class="<?= in_array($select_books_array_row['id'], $select_books_wishlist_array) ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i></div>    
              </div>            
            </div>
          </div>

        <?php  
              }
            }
            else {
              $error = true;
            }  
          }
          catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
            echo 'ERROR: ' . $e->getMessage();
          }
        } else {
          $error = true;
        }
        ?>

        </div>

        <?php
          if($error) {
            text_error();
          }
        ?>

      </div>
    </div>
  </section>
  
  <?php
  require "footer.php";
  ?>
  
  <script src="./js/main.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="./js/addAndDeleteToCartAndWishlist.js"></script>
  <script src="./js/sortBooks.js"></script>
</body>
</html>