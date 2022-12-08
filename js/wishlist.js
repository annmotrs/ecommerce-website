const buttonsCart = document.querySelectorAll('.button');
const buttonsDelete = document.querySelectorAll('.wishlist__product-delete');
const iconsMenu = document.querySelectorAll('.header__user-navigation-item');

buttonsCart.forEach(button => {
  button.addEventListener('click', (event) => {
    const currentButton = event.currentTarget;

    if(currentButton.firstElementChild.textContent === "Поместить в ") {
      addToCart(currentButton);
    }
    else {
      deleteFromCart(currentButton);
    }
  });
});

buttonsDelete.forEach(button => {
  button.addEventListener('click', (event) => {
    const currentButton = event.currentTarget;
    deleteFromWishlist(currentButton);
  });
});

//Добавляем в корзину
function addToCart(button) {
  const productId = button.closest('.wishlist__product').dataset.id;
  
  $.ajax({
    url: 'add-to-cart.php',
    type: 'POST',
    dataType: 'json',
    data: {
      product_id: productId,
    },
    success: function(data){
      if(data === true) {
        button.firstElementChild.textContent = "Убрать из "; //меняем текст кнопки
        iconsMenu[1].dataset.countCart = +iconsMenu[1].dataset.countCart + 1; //увеличиваем счетчик у иконки корзины в меню
      }
    },
    error: function(){ 
      console.log('ERROR');
    }
  })

}

//Удаляем из корзины
function deleteFromCart(button) {
  const productId = button.closest('.wishlist__product').dataset.id;
  
  $.ajax({
    url: 'delete-from-cart.php',
    type: 'POST',
    dataType: 'json',
    data: {
      product_id: productId,
    },
    success: function(data){
      if(data.user === true) {
        button.firstElementChild.textContent = "Поместить в "; //меняем текст кнопки
        iconsMenu[1].dataset.countCart = +iconsMenu[1].dataset.countCart - data.quantity; //уменьшаем счетчик у иконки корзины в меню
      }
    },
    error: function(){ 
      console.log('ERROR');
    }
  })

}

//Удаляем из избранного
function deleteFromWishlist(button) {
  const productId = button.closest('.wishlist__product').dataset.id;
  
  $.ajax({
    url: 'delete-from-wishlist.php',
    type: 'POST',
    dataType: 'json',
    data: {
      product_id: productId,
    },
    success: function(data){
      if(data === true) {
        const product = button.closest('.wishlist__product');
        product.remove(); //удаляем товар из избранного на странице
        iconsMenu[0].dataset.countWishlist = +iconsMenu[0].dataset.countWishlist - 1; //уменьшаем на единицу счетчик у иконки избранного в меню
        if(document.querySelectorAll('.wishlist__product').length === 0) { //если товаров в избранном больше нет
          document.querySelector('.wishlist__products').innerHTML = '<h1 class="wishlist__message">В избранном пусто</h1>'; //то показываем текст
        }
      }
    },
    error: function(){ 
      console.log('ERROR');
    }
  })

}