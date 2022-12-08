<header class="header">
    <div class="header__wrapper">
      <nav class="header__main-navigation">
        <div class="header__row-one container">
          <div class="header__logo">
            <a href="dashboard.php"><span>Admin</span>Panel</a>
          </div>
          <ul class="header__admin-navigation">
            <li class="header__admin-navigation-item"><a href="admin-exit.php" class="header__admin-navigation-link"><i class="fa fa-arrow-right-from-bracket header__admin-navigation-icon"></i><span class="header__admin-navigation-title">Выход</span></a></li>
            <li class="header__admin-navigation-item"><a href="" class="header__admin-navigation-link" id="menu"><i class="fa fa-bars header__admin-navigation-icon"></i></a></li>
          </ul>  
        </div>
        <div class="header__row-two">
          <ul class="header__site-navigation">
            <li class="header__site-navigation-item header__site-navigation-item1">
              <a href="products.php" class="header__site-navigation-link header__site-navigation-link1">Товары <i class="fa fa-caret-down"></i></a>
              <ul class="header__dropdown-menu">
                <li class="header__site-dropdown-menu-subitem"><a href="create-product.php" class="header__site-dropdown-menu-sublink">Добавление товара</a></li>
                <li class="header__site-dropdown-menu-subitem"><a href="products.php" class="header__site-dropdown-menu-sublink">Все товары</a></li>
              </ul>              
            </li>
            <li class="header__site-navigation-item header__site-navigation-item2">
              <a href="" class="header__site-navigation-link header__site-navigation-link2">Заказы <i class="fa fa-caret-down"></i></a>
              <ul class="header__dropdown-menu">
                <li class="header__site-dropdown-menu-subitem"><a href="new-orders.php" class="header__site-dropdown-menu-sublink">Поступившие заказы</a></li>
                <li class="header__site-dropdown-menu-subitem"><a href="active-orders.php" class="header__site-dropdown-menu-sublink">Активные заказы</a></li>
                <li class="header__site-dropdown-menu-subitem"><a href="completed-orders.php" class="header__site-dropdown-menu-sublink">Завершенные заказы</a></li>
              </ul>
            </li>
            <li class="header__site-navigation-item header__site-navigation-item3">
              <a href="" class="header__site-navigation-link header__site-navigation-link3">Отзывы <i class="fa fa-caret-down"></i></a>
              <ul class="header__dropdown-menu">
                <li class="header__site-dropdown-menu-subitem"><a href="unchecked-reviews.php" class="header__site-dropdown-menu-sublink">Непроверенные отзывы</a></li>
                <li class="header__site-dropdown-menu-subitem"><a href="checked-reviews.php" class="header__site-dropdown-menu-sublink">Проверенные отзывы</a></li>
              </ul>  
            </li>
            <li class="header__site-navigation-item header__site-navigation-item4"><a href="" class="header__site-navigation-link header__site-navigation-link4">Администраторы <i class="fa fa-caret-down"></i></a>
              <ul class="header__dropdown-menu">
                <li class="header__site-dropdown-menu-subitem"><a href="admin-register.php" class="header__site-dropdown-menu-sublink">Добавление администратора</a></li>
                <li class="header__site-dropdown-menu-subitem"><a href="admin-personal-data.php" class="header__site-dropdown-menu-sublink">Редактирование личных данных</a></li>
                <li class="header__site-dropdown-menu-subitem"><a href="admins.php" class="header__site-dropdown-menu-sublink">Все администраторы</a></li>
              </ul>  
            <li class="header__site-navigation-item header__site-navigation-item5"><a href="users.php" class="header__site-navigation-link header__site-navigation-link5">Пользователи</a></li>
          </ul>        
        </div>
      </nav>
    </div>
  </header>