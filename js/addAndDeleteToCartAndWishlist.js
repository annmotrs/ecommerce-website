const iconsMenu = document.querySelectorAll('.header__user-navigation-item');
const iconsWishlist = document.querySelectorAll('.icon-wishlist');

iconsWishlist.forEach(iconWishlist => {
  
  const productId = iconWishlist.closest('.action').dataset.id;

  iconWishlist.addEventListener('click', () => {
    if(iconWishlist.classList.contains('icon-wishlist--active')) {
      deleteFromWishlist(iconWishlist, productId);
    }
    else {
      addToWishlist(iconWishlist, productId);
    }  
  });

});


//Добавляем в избранное
function addToWishlist(iconWishlist, productId) {
  
  $.ajax({
    url: 'add-to-wishlist.php',
    type: 'POST',
    dataType: 'json',
    data: {
      product_id: productId,
    },
    success: function(data){
      if(data === true) {
        iconWishlist.classList.add('icon-wishlist--active');
        iconWishlist.firstElementChild.classList.replace('fa-regular', 'fa-solid'); //делаем иконку активной
        iconsMenu[0].dataset.countWishlist = +iconsMenu[0].dataset.countWishlist + 1; //увеличиваем на единицу счетчик у иконки избранного в меню
      }
      else {
        window.location.href = 'user-login.php'; //перенаправляем на страницу авторизации пользователя
      }
    },
    error: function(){ 
      console.log('ERROR');
    }
  })

}

//Удаляем из избранного
function deleteFromWishlist(iconWishlist, productId) {
  
  $.ajax({
    url: 'delete-from-wishlist.php',
    type: 'POST',
    dataType: 'json',
    data: {
      product_id: productId,
    },
    success: function(data){
      if(data === true) {
        iconWishlist.classList.remove('icon-wishlist--active');
        iconWishlist.firstElementChild.classList.replace('fa-solid', 'fa-regular'); //делаем иконку неактивной
        iconsMenu[0].dataset.countWishlist = +iconsMenu[0].dataset.countWishlist - 1; //уменьшаем на единицу счетчик у иконки избранного в меню
      }
      else {
        window.location.href = 'user-login.php'; //перенаправляем на страницу авторизации пользователя
      }
    },
    error: function(){ 
      console.log('ERROR');
    }
  })

}

const buttonsToCart = document.querySelectorAll('.button');
const minuses = document.querySelectorAll('.book-to-cart__minus'); 
const pluses = document.querySelectorAll('.book-to-cart__plus'); 

if(minuses.length !== 0) {
  minuses.forEach(minus => {
    minus.addEventListener('click', deleteUnitBook);
  });

  pluses.forEach(plus => {
    plus.addEventListener('click', addUnitBook);
  });

}

buttonsToCart.forEach(button => {

  button.addEventListener('click', addToCart);

});

//Увеличиваем товар на единицу
function addUnitBook(event) {
  const buttonPlus = event.currentTarget;
  const productQuantity = buttonPlus.previousElementSibling;
  const productQuantityValue = productQuantity.textContent;
  const productId = buttonPlus.closest('.action').dataset.id;

  $.ajax({
    url: 'add-unit-book-cart.php',
    type: 'POST',
    dataType: 'json',
    data: {
      product_id: productId,
      quantity: productQuantityValue,
    },
    success: function(data){
      productQuantity.textContent = data; //меняем отображаемое количество товара 
      iconsMenu[1].dataset.countCart = +iconsMenu[1].dataset.countCart + 1; //увеличиваем на единицу счетчик у иконки корзины в меню
    },
    error: function(){ 
      console.log('ERROR');
    }
  })

}

//Уменьшаем товар на единицу
function deleteUnitBook(event) {
  const buttonMinus = event.currentTarget;
  const productQuantity = buttonMinus.nextElementSibling;
  const productQuantityValue = productQuantity.textContent;
  const productId = buttonMinus.closest('.action').dataset.id;

  $.ajax({
    url: 'delete-unit-book-cart.php',
    type: 'POST',
    dataType: 'json',
    data: {
      product_id: productId,
      quantity: productQuantityValue,
    },
    success: function(data){
      if(+data === 0) {
        const parentElement = buttonMinus.closest('.book-to-cart'); 
        parentElement.previousElementSibling.textContent = "В корзину"; //меняем надпись у кнопки, которая добавляет в корзину
        parentElement.remove(); //удаляем кнопки для выбора количества товара
      } 
      else {
        productQuantity.textContent = data; //меняем отображаемое количество товара 
      }
      iconsMenu[1].dataset.countCart = +iconsMenu[1].dataset.countCart - 1; //уменьшаем на единицу счетчик у иконки корзины в меню
    },
    error: function(){ 
      console.log('ERROR');
    }
  })

}

//Добавляем в корзину
function addToCart(event) {

  const button = event.currentTarget;
  const productId = button.closest('.action').dataset.id;

  if(button.textContent === "В корзину") {
    $.ajax({
      url: 'add-to-cart.php',
      type: 'POST',
      dataType: 'json',
      data: {
        product_id: productId,
      },
      success: function(data){
        if(data === true) {
          button.textContent = "В корзине"; //меняем надпись у кнопки, которая добавляет в корзину
          //добавляем кнопки для выбора количества товара
          button.insertAdjacentHTML('afterend', `<div class="new-books__box-book-to-cart book-to-cart">  
                                                          <div class="book-to-cart__buttons-update-quantity">
                                                            <div class="book-to-cart__minus"><i class="fa-solid fa-minus"></i></div>
                                                            <div class="book-to-cart__product-quantity">1</div>
                                                            <div class="book-to-cart__plus"><i class="fa-solid fa-plus"></i></div>
                                                          </div>
                                                        </div>  `);
    
          const nextElement = button.nextElementSibling;
          const minus = nextElement.querySelector('.book-to-cart__minus');
          const plus = nextElement.querySelector('.book-to-cart__plus');
          minus.addEventListener('click', deleteUnitBook);
          plus.addEventListener('click', addUnitBook);
          iconsMenu[1].dataset.countCart = +iconsMenu[1].dataset.countCart + 1; //увеличиваем на единицу счетчик у иконки корзины в меню
        }
        else {
          window.location.href = 'user-login.php'; //перенаправляем на страницу авторизации пользователя
        }
      },
      error: function(){ 
        console.log('ERROR');
      }
    })
  }
  else {
    window.location.href = 'cart.php';
  }

}