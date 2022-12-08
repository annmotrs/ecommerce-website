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
  <title>Главная</title>
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

  <?php 

    try {
      require_once ('../db.php');
        
      $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
      $set_local->execute(); 

      //Выборки для создания статистики
      $select_products = $conn->prepare('SELECT COUNT(*) FROM shop_db.products');
      $select_products->execute(); 
      $count_products = $select_products->fetchColumn();

      $select_done_orders = $conn->prepare('SELECT COUNT(*) FROM shop_db.orders WHERE status="выполнен"');
      $select_done_orders->execute();
      $count_done_orders = $select_done_orders ->fetchColumn(); 
      
      $select_sum_done_orders = $conn->prepare('SELECT SUM(total_sum) FROM shop_db.orders WHERE status="выполнен"');
      $select_sum_done_orders->execute();
      $sum_done_orders = $select_sum_done_orders ->fetchColumn(); 
      
      $select_users = $conn->prepare('SELECT COUNT(*) FROM shop_db.users');
      $select_users->execute(); 
      $count_users = $select_users->fetchColumn();  
      
      $select_reviews = $conn->prepare('SELECT COUNT(*) FROM shop_db.reviews');
      $select_reviews->execute(); 
      $count_reviews = $select_reviews->fetchColumn();  
      
      $select_admins = $conn->prepare('SELECT COUNT(*) FROM shop_db.admins');
      $select_admins->execute(); 
      $count_admins = $select_admins->fetchColumn();   
      
      $select_products_category = $conn->prepare('SELECT categories.name, COUNT(*) AS `count` FROM shop_db.products 
                                                  LEFT JOIN shop_db.categories ON shop_db.categories.id = shop_db.products.category_id
                                                  GROUP BY categories.name
                                                  ORDER BY categories.id');
      $select_products_category->execute(); 
      $products_category_array = $select_products_category->fetchAll(PDO::FETCH_ASSOC);

      $products_name_category_array = [];
      $products_count_category_array = [];
      foreach($products_category_array as $products_category_array_row) {
        $products_name_category_array[] = $products_category_array_row['name'];
        $products_count_category_array[] = $products_category_array_row['count'];
      }

      $select_orders_status = $conn->prepare('SELECT status, COUNT(*) AS `count` FROM shop_db.orders GROUP BY status');
      $select_orders_status->execute(); 
      $orders_status_array = $select_orders_status->fetchAll(PDO::FETCH_ASSOC);

      $orders_name_status_array = [];
      $orders_count_status_array = [];
      foreach($orders_status_array as $orders_status_array_row) {
        $orders_name_status_array[] = $orders_status_array_row['status'];
        $orders_count_status_array[] = $orders_status_array_row['count'];
      }      

      $select_sold_products = $conn->prepare('SELECT title, products.id, SUM(orders_products.quantity) AS `sum` FROM shop_db.orders_products 
                                              JOIN shop_db.products ON shop_db.orders_products.product_id = shop_db.products.id
                                              JOIN shop_db.orders ON shop_db.orders.id = shop_db.orders_products.order_id
                                              WHERE shop_db.orders.status = "выполнен"
                                              GROUP BY title
                                              ORDER BY SUM(orders_products.quantity) DESC
                                              LIMIT 5');
      $select_sold_products->execute(); 
      $sold_products_array = $select_sold_products->fetchAll(PDO::FETCH_ASSOC);

      $sold_products_title_array = [];
      $sold_products_count_array = [];
      $sold_products_id_array = [];
      foreach($sold_products_array as $sold_products_array_row) {
        $sold_products_title_array[] = $sold_products_array_row['title'];
        $sold_products_sum_array[] = $sold_products_array_row['sum'];
        $sold_products_id_array[] = $sold_products_array_row['id'];
      }   
      
      $select_profitable_products = $conn->prepare('SELECT title, products.id, SUM(orders_products.sum) AS `sum` FROM shop_db.orders_products 
                                              JOIN shop_db.products ON shop_db.orders_products.product_id = shop_db.products.id
                                              JOIN shop_db.orders ON shop_db.orders.id = shop_db.orders_products.order_id
                                              WHERE shop_db.orders.status = "выполнен"
                                              GROUP BY title
                                              ORDER BY SUM(orders_products.sum) DESC
                                              LIMIT 5');
      $select_profitable_products->execute(); 
      $profitable_products_array = $select_profitable_products->fetchAll(PDO::FETCH_ASSOC);

      $profitable_products_title_array = [];
      $profitable_products_count_array = [];
      $profitable_products_id_array = [];
      foreach($profitable_products_array as $profitable_products_array_row) {
        $profitable_products_title_array[] = $profitable_products_array_row['title'];
        $profitable_products_sum_array[] = $profitable_products_array_row['sum'];
        $profitable_products_id_array[] = $profitable_products_array_row['id'];
      }    
      
      $select_reviews_status = $conn->prepare('SELECT status, COUNT(*) AS `count` FROM shop_db.reviews GROUP BY status');
      $select_reviews_status->execute(); 
      $reviews_status_array = $select_reviews_status->fetchAll(PDO::FETCH_ASSOC);

      $reviews_name_status_array = [];
      $reviews_count_status_array = [];
      foreach($reviews_status_array as $reviews_status_array_row) {
        $reviews_name_status_array[] = $reviews_status_array_row['status'];
        $reviews_count_status_array[] = $reviews_status_array_row['count'];
      }   

      
      $select_reviews_high_grade = $conn->prepare('SELECT title, products.id, AVG(grade) AS `grade` FROM shop_db.reviews 
                                              JOIN shop_db.products ON shop_db.reviews.product_id = shop_db.products.id
                                              WHERE shop_db.reviews.status <> "отклонено"
                                              GROUP BY title
                                              ORDER BY AVG(grade) DESC
                                              LIMIT 5');
      $select_reviews_high_grade->execute(); 
      $reviews_high_grade_array = $select_reviews_high_grade->fetchAll(PDO::FETCH_ASSOC);

      $reviews_high_grade_title_array = [];
      $reviews_high_grade_grade_array = [];
      $reviews_high_grade_id_array = [];
      foreach($reviews_high_grade_array as $reviews_high_grade_array_row) {
        $reviews_high_grade_title_array[] = $reviews_high_grade_array_row['title'];
        $reviews_high_grade_grade_array[] = $reviews_high_grade_array_row['grade'];
        $reviews_high_grade_id_array[] = $reviews_high_grade_array_row['id'];
      }   

      $select_count_reviews = $conn->prepare('SELECT title, products.id, COUNT(*) AS `count` FROM shop_db.reviews 
                                                  JOIN shop_db.products ON shop_db.reviews.product_id = shop_db.products.id
                                                  WHERE shop_db.reviews.status <> "отклонено"
                                                  GROUP BY title
                                                  ORDER BY COUNT(*) DESC
                                                  LIMIT 5');
      $select_count_reviews->execute(); 
      $count_reviews_array_ = $select_count_reviews->fetchAll(PDO::FETCH_ASSOC);

      $count_reviews_title_array = [];
      $count_reviews_count_array = [];
      $count_reviews_id_array = [];
      foreach($count_reviews_array_ as $count_reviews_array_row) {
        $count_reviews_title_array[] = $count_reviews_array_row['title'];
        $count_reviews_count_array[] = $count_reviews_array_row['count'];
        $count_reviews_id_array[] = $count_reviews_array_row['id'];
      }   

      $select_user_gender = $conn->prepare('SELECT gender, COUNT(*) AS `count` FROM shop_db.users GROUP BY gender');
      $select_user_gender->execute(); 
      $user_gender_array = $select_user_gender->fetchAll(PDO::FETCH_ASSOC);

      $user_name_gender_array = [];
      $user_count_gender_array = [];
      foreach($user_gender_array as $user_gender_array_row) {
        $user_name_gender_array[] = $user_gender_array_row['gender'];
        $user_count_gender_array[] = $user_gender_array_row['count'];
      }   

      $select_user_city = $conn->prepare('SELECT city, COUNT(*) AS `count` FROM shop_db.users GROUP BY city ORDER BY COUNT(*) DESC LIMIT 5');
      $select_user_city->execute(); 
      $user_city_array = $select_user_city->fetchAll(PDO::FETCH_ASSOC);

      $user_name_city_array = [];
      $user_count_city_array = [];
      foreach($user_city_array as $user_city_array_row) {
        $user_name_city_array[] = $user_city_array_row['city'];
        $user_count_city_array[] = $user_city_array_row['count'];
      }   

      $select_user_age = $conn->prepare('SELECT 
                                          CASE
                                            WHEN TIMESTAMPDIFF(Year, birthday, CURDATE()) < 18
                                              THEN "меньше 18"
                                            WHEN TIMESTAMPDIFF(Year, birthday, CURDATE()) >= 18 AND TIMESTAMPDIFF(Year, birthday, CURDATE()) < 36
                                              THEN "18 - 35"
                                            WHEN TIMESTAMPDIFF(Year, birthday, CURDATE()) >= 36 AND TIMESTAMPDIFF(Year, birthday, CURDATE()) <= 50
                                              THEN "36 - 50"
                                            ELSE "больше 50"
                                          END AS `age`,
                                          COUNT(*) AS `count`
                                        FROM shop_db.users
                                        GROUP BY
                                        CASE
                                          WHEN TIMESTAMPDIFF(Year, birthday, CURDATE()) < 18
                                            THEN "меньше 18"
                                          WHEN TIMESTAMPDIFF(Year, birthday, CURDATE()) >= 18 AND TIMESTAMPDIFF(Year, birthday, CURDATE()) < 36
                                            THEN "18 - 35"
                                          WHEN TIMESTAMPDIFF(Year, birthday, CURDATE()) >= 36 AND TIMESTAMPDIFF(Year, birthday, CURDATE()) <= 50
                                            THEN "36 - 50"
                                          ELSE "больше 50"
                                        END');
      $select_user_age->execute(); 
      $user_age_array = $select_user_age->fetchAll(PDO::FETCH_ASSOC);

      $user_name_age_array = [];
      $user_count_age_array = [];
      foreach($user_age_array as $user_age_array_row) {
        $user_name_age_array[] = $user_age_array_row['age'];
        $user_count_age_array[] = $user_age_array_row['count'];
      }

    }   
    catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
      echo 'ERROR: ' . $e->getMessage();
    }  

  ?>    

  <section class="dashboard-cards container">

    <div class="dashboard-cards__card">
      <div class="dashboard-cards__text">
        <div class="dashboard-cards__number"><?= $count_products; ?></div>
        <div class="dashboard-cards__caption">Количество добавленных товаров</div>
      </div>
      <div class="dashboard-cards__icon">
        <i class="fa-solid fa-book"></i>
      </div>
    </div>

    <div class="dashboard-cards__card">
      <div class="dashboard-cards__text">
        <div class="dashboard-cards__number"><?= $count_done_orders; ?></div>
        <div class="dashboard-cards__caption">Количество выполненных заказов</div>
      </div>
      <div class="dashboard-cards__icon">
        <i class="fa-sharp fa-solid fa-box"></i>
      </div>
    </div>

    <div class="dashboard-cards__card">
      <div class="dashboard-cards__text">
        <div class="dashboard-cards__number"><?= $sum_done_orders; ?> руб.</div>
        <div class="dashboard-cards__caption">Общая сумма на которую куплены товары</div>
      </div>
      <div class="dashboard-cards__icon">
        <i class="fa-solid fa-ruble-sign"></i>
      </div>
    </div>

    <div class="dashboard-cards__card">
      <div class="dashboard-cards__text">
        <div class="dashboard-cards__number"><?= $count_users; ?></div>
        <div class="dashboard-cards__caption">Количество зарегистрированных пользователей</div>
      </div>
      <div class="dashboard-cards__icon">
        <i class="fa-solid fa-user"></i>
      </div>
    </div>

    <div class="dashboard-cards__card">
      <div class="dashboard-cards__text">
        <div class="dashboard-cards__number"><?= $count_reviews; ?></div>
        <div class="dashboard-cards__caption">Количество отзывов на товары</div>
      </div>
      <div class="dashboard-cards__icon">
        <i class="fa-solid fa-comment"></i>
      </div>
    </div>

    <div class="dashboard-cards__card">
      <div class="dashboard-cards__text">
        <div class="dashboard-cards__number"><?= $count_admins; ?></div>
        <div class="dashboard-cards__caption">Количество администраторов</div>
      </div>
      <div class="dashboard-cards__icon">
        <i class="fa-solid fa-user-secret"></i>
      </div>
    </div>

  </section>

  <section class="dashboard-section container">
    <h1 class="dashboard-section__title">Товары</h1>
    <div class="dashboard-section__chart dashboard-section__chart--pie">
      <h2 class="dashboard-section__chart-title">Товары по категориям</h2>
      <div class="dashboard-section__chart-wrapper" data-names="<?= implode(", ", $products_name_category_array) ?>" data-info="<?= implode(", ", $products_count_category_array) ?>">
        <canvas id="chart1"></canvas>
      </div>
    </div>
  </section>

  <section class="dashboard-section container">
    <h1 class="dashboard-section__title">Заказы</h1>
    <div class="dashboard-section__row">

      <div class="dashboard-section__chart dashboard-section__chart--pie">
        <h2 class="dashboard-section__chart-title">Заказы по статусам</h2>
        <div class="dashboard-section__chart-wrapper" data-names="<?= implode(", ", $orders_name_status_array) ?>" data-info="<?= implode(", ", $orders_count_status_array) ?>">
          <canvas id="chart2"></canvas>
        </div>
      </div>

      <div class="dashboard-section__chart dashboard-section__chart--bar">
        <h2 class="dashboard-section__chart-title">Самые продаваемые товары</h2>
        <div class="dashboard-section__chart-wrapper" data-names="<?= implode(", ", $sold_products_title_array) ?>" data-info="<?= implode(", ", $sold_products_sum_array) ?>" data-id="<?= implode(", ", $sold_products_id_array) ?>">
          <canvas id="chart3"></canvas>
        </div>
      </div>

    </div>

    <div class="dashboard-section__chart dashboard-section__chart--bar">
      <h2 class="dashboard-section__chart-title">Самые прибыльные товары</h2>
      <div class="dashboard-section__chart-wrapper" data-names="<?= implode(", ", $profitable_products_title_array) ?>" data-info="<?= implode(", ", $profitable_products_sum_array) ?>" data-id="<?= implode(", ", $profitable_products_id_array) ?>">
        <canvas id="chart4"></canvas>
      </div>
    </div>

  </section>

  <section class="dashboard-section container">
    <h1 class="dashboard-section__title">Отзывы</h1>
    <div class="dashboard-section__row">

      <div class="dashboard-section__chart dashboard-section__chart--pie">
        <h2 class="dashboard-section__chart-title">Отзывы по статусам</h2>
        <div class="dashboard-section__chart-wrapper" data-names="<?= implode(", ", $reviews_name_status_array) ?>" data-info="<?= implode(", ", $reviews_count_status_array) ?>">
          <canvas id="chart5"></canvas>
        </div>
      </div>

      <div class="dashboard-section__chart dashboard-section__chart--bar">
        <h2 class="dashboard-section__chart-title">Товары с самой высокой оценкой</h2>
        <div class="dashboard-section__chart-wrapper" data-names="<?= implode(", ", $reviews_high_grade_title_array) ?>" data-info="<?= implode(", ", $reviews_high_grade_grade_array) ?>" data-id="<?= implode(", ", $reviews_high_grade_id_array) ?>">
          <canvas id="chart6"></canvas>
        </div>
      </div>

    </div>

    <div class="dashboard-section__chart dashboard-section__chart--bar">
      <h2 class="dashboard-section__chart-title">Самые популярные товары по количеству отзывов</h2>
      <div class="dashboard-section__chart-wrapper" data-names="<?= implode(", ", $count_reviews_title_array) ?>" data-info="<?= implode(", ", $count_reviews_count_array) ?>" data-id="<?= implode(", ", $count_reviews_id_array) ?>">
        <canvas id="chart7"></canvas>
      </div>
    </div>

  </section>

  <section class="dashboard-section container">
    <h1 class="dashboard-section__title">Пользователи</h1>
    <div class="dashboard-section__row">

      <div class="dashboard-section__chart dashboard-section__chart--pie">
        <h2 class="dashboard-section__chart-title">Пользователи по полу</h2>
        <div class="dashboard-section__chart-wrapper" data-names="<?= implode(", ", $user_name_gender_array) ?>" data-info="<?= implode(", ", $user_count_gender_array) ?>">
          <canvas id="chart8"></canvas>
        </div>
      </div>

      <div class="dashboard-section__chart dashboard-section__chart--pie">
        <h2 class="dashboard-section__chart-title">Пользователи по возрасту</h2>
        <div class="dashboard-section__chart-wrapper" data-names="<?= implode(", ", $user_name_age_array) ?>" data-info="<?= implode(", ", $user_count_age_array) ?>">
          <canvas id="chart9"></canvas>
        </div>
      </div>

    </div>

    <div class="dashboard-section__chart dashboard-section__chart--bar">
      <h2 class="dashboard-section__chart-title">Города, где проживают пользователи</h2>
      <div class="dashboard-section__chart-wrapper" data-names="<?= implode(", ", $user_name_city_array) ?>" data-info="<?= implode(", ", $user_count_city_array) ?>">
        <canvas id="chart10"></canvas>
      </div>
    </div>

  </section>
  
  <script src="../js/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="../js/dashboard.js"></script>

</body>
</html>