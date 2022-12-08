<header class="header">
    <div class="header__wrapper">
      <nav class="header__main-navigation">
        <div class="header__row-one container">
          <div class="header__logo">
            <a href="index.php"><i class="fa fa-graduation-cap"></i> Умбук</a>
          </div>
          <ul class="header__user-navigation">

          <?php 
            $count_wishlist = 0;
            $count_cart = 0;
            if(isset($_SESSION['user_id'])){
              $user_id = $_SESSION['user_id'];

              try {
                require_once ('db.php');
        
                
                $set_local = $conn->prepare('SET lc_time_names="ru_RU"'); 
                $set_local->execute(); 

                //Находим количество товаров в избранном
                $select_wishlist = $conn->prepare('SELECT COUNT(*) FROM shop_db.wishlist WHERE user_id=:user_id');
                $select_wishlist ->bindParam(':user_id', $user_id);
                $select_wishlist ->execute(); 
                $count_wishlist = $select_wishlist->fetchColumn();

                //Находим количество товаров в корзине
                $select_books_cart = $conn->prepare('SELECT SUM(quantity) FROM shop_db.cart WHERE user_id=:user_id');
                $select_books_cart ->bindParam(':user_id', $user_id);
                $select_books_cart ->execute();
                $count_cart = $select_books_cart->fetchColumn(); 
                if($count_cart === NULL) {
                  $count_cart = 0;
                }
                
              }   
              catch(PDOException $e) { //отлавливаем ошибку и выводим сообщение
                echo 'ERROR: ' . $e->getMessage();
              }  

            }
          ?>  
            
            <li class="header__user-navigation-item" data-count-wishlist="<?= $count_wishlist ?>"><a href="wishlist.php" class="header__user-navigation-link"><i class="fa fa-heart header__user-navigation-icon"></i><span class="header__user-navigation-title">Избранное</span></a></li>
            <li class="header__user-navigation-item" data-count-cart="<?= $count_cart ?>"><a href="cart.php" class="header__user-navigation-link"><i class="fa fa-shopping-cart header__user-navigation-icon"></i><span class="header__user-navigation-title">Корзина</span></a></li>
            
            <?php 
            if(isset($_SESSION['user_id'])){
              echo '<li class="header__user-navigation-item"><a href="user-settings.php" class="header__user-navigation-link"><i class="fa fa-user-gear header__user-navigation-icon"></i><span class="header__user-navigation-title">Личный кабинет</span></a></li>
                    <li class="header__user-navigation-item"><a href="exit.php" class="header__user-navigation-link"><i class="fa fa-arrow-right-from-bracket header__user-navigation-icon"></i><span class="header__user-navigation-title">Выход</span></a></li>';
            }
            else {
              echo '<li class="header__user-navigation-item"><a href="user-register.php" class="header__user-navigation-link"><i class="fa fa-user header__user-navigation-icon"></i><span class="header__user-navigation-title">Регистрация</span></a></li>
                    <li class="header__user-navigation-item"><a href="user-login.php" class="header__user-navigation-link"><i class="fa fa-sign-in header__user-navigation-icon"></i><span class="header__user-navigation-title">Вход</span></a></li>';
            } 
            ?> 

            <li class="header__user-navigation-item"><a href="" class="header__user-navigation-link" id="menu"><i class="fa fa-bars header__user-navigation-icon"></i></a></li>
          </ul>  
        </div>
        <div class="header__row-two">
          <ul class="header__site-navigation">
            <li class="header__site-navigation-item header__site-navigation-item1"><a href="books.php?category_id=1" class="header__site-navigation-link header__site-navigation-link1">Дошкольные пособия</a></li>
            <li class="header__site-navigation-item header__site-navigation-item2">
              <a href="" class="header__site-navigation-link header__site-navigation-link2">Школьные пособия <i class="fa fa-caret-down"></i></a>
              <ul class="header__dropdown-menu">
                <li class="header__site-dropdown-menu-subitem"><a href="books.php?category_id=2" class="header__site-dropdown-menu-sublink">1 класс</a></li>
                <li class="header__site-dropdown-menu-subitem"><a href="books.php?category_id=3" class="header__site-dropdown-menu-sublink">2 класс</a></li>
                <li class="header__site-dropdown-menu-subitem"><a href="books.php?category_id=4" class="header__site-dropdown-menu-sublink">3 класс</a></li>
                <li class="header__site-dropdown-menu-subitem"><a href="books.php?category_id=5" class="header__site-dropdown-menu-sublink">4 класс</a></li>
                <li class="header__site-dropdown-menu-subitem"><a href="books.php?category_id=6" class="header__site-dropdown-menu-sublink">5 класс</a></li>
                <li class="header__site-dropdown-menu-subitem"><a href="books.php?category_id=7" class="header__site-dropdown-menu-sublink">6 класс</a></li>
                <li class="header__site-dropdown-menu-subitem"><a href="books.php?category_id=8" class="header__site-dropdown-menu-sublink">7 класс</a></li>
                <li class="header__site-dropdown-menu-subitem"><a href="books.php?category_id=9" class="header__site-dropdown-menu-sublink">8 класс</a></li>
                <li class="header__site-dropdown-menu-subitem"><a href="books.php?category_id=10" class="header__site-dropdown-menu-sublink">9 класс</a></li>
                <li class="header__site-dropdown-menu-subitem"><a href="books.php?category_id=11" class="header__site-dropdown-menu-sublink">10 класс</a></li>
                <li class="header__site-dropdown-menu-subitem"><a href="books.php?category_id=12" class="header__site-dropdown-menu-sublink">11 класс</a></li>
              </ul>
            </li>
            <li class="header__site-navigation-item header__site-navigation-item3">
              <a href="" class="header__site-navigation-link header__site-navigation-link3">Книги для экзаменов <i class="fa fa-caret-down"></i></a>
              <ul class="header__dropdown-menu">
                <li class="header__site-dropdown-menu-subitem"><a href="books.php?category_id=13" class="header__site-dropdown-menu-sublink">ВПР</a></li>
                <li class="header__site-dropdown-menu-subitem"><a href="books.php?category_id=14" class="header__site-dropdown-menu-sublink">ОГЭ</a></li>
                <li class="header__site-dropdown-menu-subitem"><a href="books.php?category_id=15" class="header__site-dropdown-menu-sublink">ЕГЭ</a></li>
              </ul>  
            </li>
            <li class="header__site-navigation-item header__site-navigation-item4"><a href="books.php?category_id=16" class="header__site-navigation-link header__site-navigation-link4">Пособия для студентов</a></li>
          </ul>        
        </div>
      </nav>
    </div>
  </header>