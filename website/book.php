<?php
session_start();

function text_error() {
  echo('<h1 class="book__message">Книга не найдена!</h1>');
}  
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Книга</title>
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

  <section class="book">
    <div class="book__wrapper">


    <?php

    if(!empty($_GET['product_id'])){

      try {
        require_once ('db.php');

        $product_id = $_GET['product_id'];
        $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
        $set_local->execute(); 

        //Делаем выборку данных товара по его id
        $select_book = $conn->prepare('SELECT products.*, disciplines.name AS discipline_name, publishers.name AS publisher_name, bindings.name AS binding_name FROM shop_db.products 
                                      JOIN shop_db.disciplines ON shop_db.products.discipline_id = shop_db.disciplines.id 
                                      JOIN shop_db.publishers ON shop_db.products.publisher_id = shop_db.publishers.id 
                                      JOIN shop_db.bindings ON shop_db.products.binding_id = shop_db.bindings.id 
                                      WHERE shop_db.products.id=:id');
        $select_book ->bindParam(':id', $product_id);
        $select_book ->execute(); 

        if($select_book->rowCount() > 0 ) {
          $select_book_array = $select_book->fetch(PDO::FETCH_ASSOC); 

          $select_book_category = $conn->prepare('SELECT * FROM shop_db.categories WHERE id=:id');
          $select_book_category ->bindParam(':id', $select_book_array['category_id']);
          $select_book_category ->execute(); 
          $select_book_category_array = $select_book_category->fetch(PDO::FETCH_ASSOC); 

          //Делаем выборку всех авторов данной книги
          $select_book_authors = $conn->prepare('SELECT authors.name FROM shop_db.products_authors 
                                                JOIN shop_db.authors ON shop_db.products_authors.author_id = shop_db.authors.id 
                                                WHERE shop_db.products_authors.product_id=:id');
          $select_book_authors ->bindParam(':id', $select_book_array['id']);
          $select_book_authors ->execute(); 
          $select_book_authors_array = $select_book_authors->fetchAll(PDO::FETCH_COLUMN); 

          $num_rows_wishlist = 0;
          $quantity_in_cart = 0;
          if(isset($_SESSION['user_id'])){
            $user_id = $_SESSION['user_id'];
            //Проверяем есть ли этот товар у пользователя в избранном
            $select_wishlist = $conn->prepare('SELECT COUNT(*) FROM shop_db.wishlist WHERE product_id=:product_id AND user_id=:user_id');
            $select_wishlist ->bindParam(':product_id', $product_id);
            $select_wishlist ->bindParam(':user_id', $user_id);
            $select_wishlist ->execute(); 
            $num_rows_wishlist = $select_wishlist->fetchColumn();   
            //Проверяем есть ли этот товар у пользователя в корзине и в каком количестве
            $select_books_cart = $conn->prepare('SELECT quantity FROM shop_db.cart WHERE product_id=:product_id AND user_id=:user_id');
            $select_books_cart ->bindParam(':product_id', $product_id);
            $select_books_cart ->bindParam(':user_id', $user_id);
            $select_books_cart ->execute();
            $quantity_in_cart = $select_books_cart->fetchColumn(); 
          }
         

      ?>

      <h1 class="book__book-title"><?= $select_book_array['title']; ?></h1>
      <div class="book__content">
        <div class="book__column">
          <div class="book__box-image"><img src="./uploads/<?= $select_book_array['photo']; ?>" alt="<?= $select_book_array['title']; ?>" class="book__image"></div>
          <a href="reviews.php?product_id=<?= $product_id; ?>" class="book__button-reviews">Посмотреть отзывы</a>
          <div class="book__action action" data-id="<?= $product_id; ?>">
            <button type="button" class="book__button button"><?= $quantity_in_cart ? "В корзине" : "В корзину" ?></button>
            <?php if($quantity_in_cart) {
                    echo "<div class='book__box-book-to-cart book-to-cart'>  
                            <div class='book-to-cart__buttons-update-quantity'>
                              <div class='book-to-cart__minus'><i class='fa-solid fa-minus'></i></div>
                              <div class='book-to-cart__product-quantity'>$quantity_in_cart</div>
                              <div class='book-to-cart__plus'><i class='fa-solid fa-plus'></i></div>
                            </div>
                          </div>";  
                  }
            ?>
            <div class="book__icon-wishlist icon-wishlist<?= $num_rows_wishlist === 1 ? ' icon-wishlist--active' : ''; ?>"><i class="<?= $num_rows_wishlist === 1 ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i></div>   
          </div>   
        </div>
        <div class="book__column">
          
          <p class="book__price"><?= $select_book_array['price']; ?> руб.</p>
          <h2 class="book__title">Информация</h2>
          <p class="book__book-property">Категория: <a href="books.php?category_id=<?= $select_book_category_array['id']; ?>" class="book__category-link"><?= $select_book_category_array['name']; ?></span></a>
          <p class="book__book-property">Автор: <span class="book__book-value"><?= implode(", ", $select_book_authors_array); ?></span></p>
          <p class="book__book-property">Количество страниц: <span class="book__book-value"><?= $select_book_array['num_pages']; ?></span></p>
          <p class="book__book-property">Год выпуска: <span class="book__book-value"><?= $select_book_array['year_of_publication']; ?></span></p>
          <p class="book__book-property">Предмет: <span class="book__book-value"><?= $select_book_array['discipline_name']; ?></span></p>
          <p class="book__book-property">Издательство: <span class="book__book-value"><?= $select_book_array['publisher_name']; ?></span></p>
          <p class="book__book-property">Переплет: <span class="book__book-value"><?= $select_book_array['binding_name']; ?></span></p>
          <h2 class="book__title">Описание</h2>
          <p class="book__description"><?= $select_book_array['description']; ?></p>
        </div>
      </div>

    <?php  
        }
        else {
          text_error();
        }  
      }
      catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
        echo 'ERROR: ' . $e->getMessage();
      }
    }  
    else {
      text_error();
    }
    ?>

      
    </div>
  </section>
  
  <?php
  require "footer.php";
  ?>
  
  <script src="./js/main.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="./js/addAndDeleteToCartAndWishlist.js"></script>
</body>
</html>