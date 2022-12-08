<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Главная страница</title>
  <link rel="icon" type="image/x-icon" href="./images/icon.ico">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/media.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
</head>
<body>

  <?php
  require "header.php";
  ?>

  <main class="main">
    <div class="container">
      <div class="main__wrapper">
        <section class="main__about about">
          <div class="about__column">
            <div class="swiper about__slider">
              <div class="swiper-wrapper">
                <div class="about__slide swiper-slide">
                  <img src="./images/slider-card1.jpg" alt="">
                </div>
                <div class="about__slide swiper-slide">
                  <img src="./images/slider-card2.jpg" alt="">
                </div>
                <div class="about__slide swiper-slide">
                  <img src="./images/slider-card3.jpg" alt="">
                </div>
              </div>
              <div class="swiper-button-next"></div>
              <div class="swiper-button-prev"></div>
              <div class="swiper-pagination"></div>
            </div>
            <div class="about__cards">
              <div class="about__card">
                <div class="about__card-icon"><i class="fa fa-truck-fast"></i></div>
                <div class="about__card-title">Доставка по всей России</div>
                <div class="about__card-text">Товар будет в любой точке страны через несколько дней</div>
              </div>
              <div class="about__card">
                <div class="about__card-icon"><i class="fa fa-credit-card"></i></div>
                <div class="about__card-title">Оплата при получении</div>
                <div class="about__card-text">Вы платите за товар только тогда, когда его получаете</div>
              </div>
              <div class="about__card">
                <div class="about__card-icon"><i class="fa fa-book"></i></div>
                <div class="about__card-title">Большой выбор учебников</div>
                <div class="about__card-text">Здесь вы найдете всю нужную вам учебную литературу</div>
              </div>
            </div>
          </div>
          <div class="about__column">
            <div class="about__content">
              <h2 class="about__title">Интернет-магазин учебной литературы «Умбук»</h2>
              <p class="about__text"><b>Где выгодно купить учебник?</b></p>
              <p class="about__text">Этим вопросом наверняка не раз задавался каждый родитель, студент или педагог.</p>
              <p class="about__text">Купить учебники и сэкономить можно, посетив интернет-магазин учебников «Умбук». Здесь вас ждет огромный выбор учебной литературы для школьников с первого по одиннадцатый класс, а также всевозможная литература для студентов. В магазине учебной литературы «Умбук» вы также можете купить рабочую тетрадь, школьные учебники, учебные пособия и многое другое.</p>
              <p class="about__text"><b>Почему покупать книги в интернет-магазине выгодно?</b></p>
              <p class="about__text">Если вы любите экономить деньги и не тратить при этом много времени, то интернет-магазин школьных учебников «Умбук» создан для вас. Приобретая учебную литературу с помощью интернет-магазина «Умбук», вы можете сэкономить до 40% вашего бюджета!</p>
            </div>
          </div>  
        </section>
        <section class="main__new-books new-books">
          <div class="new-books__wrapper">
            <h1 class="new-books__title title">Новинки</h1>
            <div class="new-books__cards">

            <?php

              try {
                require_once ('db.php');
    
                $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
                $set_local->execute(); 

                //Делаем выборку четырех новых товаров
                $select_new_books = $conn->prepare('SELECT products.*, disciplines.name AS discipline_name, publishers.name AS publisher_name, categories.name AS category_name FROM shop_db.products 
                                                    JOIN shop_db.disciplines ON shop_db.products.discipline_id = shop_db.disciplines.id 
                                                    JOIN shop_db.publishers ON shop_db.products.publisher_id = shop_db.publishers.id 
                                                    JOIN shop_db.categories ON shop_db.products.category_id = shop_db.categories.id             
                                                    WHERE is_sale = 1 ORDER BY publication_date DESC LIMIT 4');
                $select_new_books ->execute(); 

                if($select_new_books->rowCount() > 0 ) {
                  $select_new_books_array = $select_new_books->fetchAll(); 

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

                  //Если товары найдены, то выводим их
                  foreach($select_new_books_array as $select_new_books_array_row) { 

                    //Делаем выборку всех авторов данной книги
                    $select_book_authors = $conn->prepare('SELECT authors.name FROM shop_db.products_authors 
                                                          JOIN shop_db.authors ON shop_db.products_authors.author_id = shop_db.authors.id 
                                                          WHERE shop_db.products_authors.product_id=:id');
                    $select_book_authors ->bindParam(':id', $select_new_books_array_row['id']);
                    $select_book_authors ->execute(); 
                    $select_book_authors_array = $select_book_authors->fetchAll(PDO::FETCH_COLUMN);                       
                
              ?>

              <div class="new-books__card">
                <div class="new-books__card-title"><a href="book.php?product_id=<?= $select_new_books_array_row['id']; ?>" class="new-books__card-link"><?= $select_new_books_array_row['title']; ?></a></div>
                <div class="new-books__card-columns">
                  <div class="new-books__card-image">
                    <img src="./uploads/<?= $select_new_books_array_row['photo']; ?>" alt="<?= $select_new_books_array_row['title']; ?>">
                  </div>
                  <div class="new-books__card-content">
                    <div class="new-books__card-author"><?= implode(", ", $select_book_authors_array); ?></div>
                    <div class="new-books__card-category"><?= $select_new_books_array_row['category_name']; ?></div>
                    <div class="new-books__card-property">Предмет: <span class="new-books__card-value"><?= $select_new_books_array_row['discipline_name']; ?></span></div>
                    <div class="new-books__card-property">Издательство: <span class="new-books__card-value"><?= $select_new_books_array_row['publisher_name']; ?></span></div>
                    <div class="new-books__card-price"><?= $select_new_books_array_row['price']; ?> pуб.</div>
                    <div class="new-books__action action" data-id="<?= $select_new_books_array_row['id']; ?>">
                      <div class="new-books__wrapper-action">
                        <button type="button" class="new-books__button button"><?= array_key_exists($select_new_books_array_row['id'], $select_books_cart_array) ? "В корзине" : "В корзину" ?></button>
                        <?php if(array_key_exists($select_new_books_array_row['id'], $select_books_cart_array)) {
                          $id = $select_new_books_array_row['id'];
                          echo "<div class='new-books__box-book-to-cart book-to-cart'>  
                                  <div class='book-to-cart__buttons-update-quantity'>
                                    <div class='book-to-cart__minus'><i class='fa-solid fa-minus'></i></div>
                                    <div class='book-to-cart__product-quantity'>$select_books_cart_array[$id]</div>
                                    <div class='book-to-cart__plus'><i class='fa-solid fa-plus'></i></div>
                                  </div>
                                </div>";  
                        }
                        ?>
                        <div class="new-books__icon-wishlist icon-wishlist<?= in_array($select_new_books_array_row['id'], $select_books_wishlist_array) ? ' icon-wishlist--active' : ''; ?>"><i class="<?= in_array($select_new_books_array_row['id'], $select_books_wishlist_array) ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i></div>    
                      </div>
                    </div>
                  </div>  
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
          </div>
        </section>
      </div>
    </div> 
  </main>
  
  <?php
  require "footer.php";
  ?>
  
  <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
  <script>
    var swiper = new Swiper(".about__slider", {
      slidesPerView: 1,
      spaceBetween: 5,
      loop: true,
      autoplay: true,
      grabCursor: true,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
  </script>
  <script src="./js/main.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="./js/addAndDeleteToCartAndWishlist.js"></script>
</body>
</html>