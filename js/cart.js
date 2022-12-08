const buttonsDelete = document.querySelectorAll('.cart__product-delete');
const minuses = document.querySelectorAll('.book-to-cart__minus'); 
const pluses = document.querySelectorAll('.book-to-cart__plus'); 
const buttonOrder = document.querySelector('.button');
const iconsMenu = document.querySelectorAll('.header__user-navigation-item');

buttonsDelete.forEach(button => {
  button.addEventListener('click', (event) => {
    const currentButton = event.currentTarget;
    deleteFromCart(currentButton);
  });
});

minuses.forEach(minus => {
  minus.addEventListener('click', deleteUnitBook);
});

pluses.forEach(plus => {
  plus.addEventListener('click', addUnitBook);
});

buttonOrder.addEventListener('click', () => window.location.href = 'order.php');

//Удаление товара из корзины
function deleteFromCart(button) {
  const productId = button.closest('.cart__product').dataset.id;
  
  $.ajax({
    url: 'delete-from-cart.php',
    type: 'POST',
    dataType: 'json',
    data: {
      product_id: productId,
    },
    success: function(data){
      if(data.user === true) {
        const product = button.closest('.cart__product');
        const priceElement = product.querySelector('.cart__product-price-value');
        let totalSumElement = document.querySelector('.cart__final-sum-value');
        totalSumElement.textContent = totalSumElement.textContent - priceElement.textContent * data.quantity; //изменяем общую стоимость
        product.remove(); //удаляем товар из корзины
        iconsMenu[1].dataset.countCart = +iconsMenu[1].dataset.countCart - data.quantity; //уменьшаем счетчик у иконки корзины в меню
        if(document.querySelectorAll('.cart__product').length === 0) { //если товаров не осталось
          document.querySelector('.cart__products').innerHTML = '<h1 class="cart__message">Корзина пуста</h1>'; //показываем надпись
          document.querySelector('.cart__final-sum').remove(); //удаляем текст с общей стоимостью
          document.querySelector('.button').remove(); //удаляем кнопку для оформления заказа
        }
      }
    },
    error: function(){ 
      console.log('ERROR');
    }
  })

}

//Увеличиваем товар на единицу
function addUnitBook(event) {
  const buttonPlus = event.currentTarget;
  const productQuantity = buttonPlus.previousElementSibling;
  const productQuantityValue = productQuantity.textContent;
  const productId = buttonPlus.closest('.cart__product').dataset.id;
  const parentElement = buttonPlus.closest('.cart__product');
  const priceElement = parentElement.querySelector('.cart__product-price-value');
  let sumElement = parentElement.querySelector('.cart__product-sum-value');
  let totalSumElement = document.querySelector('.cart__final-sum-value');

  $.ajax({
    url: 'add-unit-book-cart.php',
    type: 'POST',
    dataType: 'json',
    data: {
      product_id: productId,
      quantity: productQuantityValue,
    },
    success: function(data){
      productQuantity.textContent = data;
      sumElement.textContent = priceElement.textContent * data; //изменяем сумму товара
      totalSumElement.textContent = +totalSumElement.textContent + +priceElement.textContent; //изменяем общую стоимость
      iconsMenu[1].dataset.countCart = +iconsMenu[1].dataset.countCart + 1; //увеличиваем счетчик у иконки корзины в меню
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
  const productId = buttonMinus.closest('.cart__product').dataset.id;
  const parentElement = buttonMinus.closest('.cart__product');
  const priceElement = parentElement.querySelector('.cart__product-price-value');
  let sumElement = parentElement.querySelector('.cart__product-sum-value');
  let totalSumElement = document.querySelector('.cart__final-sum-value');

  if(+productQuantityValue > 1) {
    $.ajax({
      url: 'delete-unit-book-cart-up-to-one.php',
      type: 'POST',
      dataType: 'json',
      data: {
        product_id: productId,
        quantity: productQuantityValue,
      },
      success: function(data){
        productQuantity.textContent = data;
        sumElement.textContent = priceElement.textContent * data; //изменяем сумму товара
        totalSumElement.textContent = totalSumElement.textContent - priceElement.textContent; //изменяем общую стоимость
        iconsMenu[1].dataset.countCart = +iconsMenu[1].dataset.countCart - 1; //уменьшаем счетчик у иконки корзины в меню
      },
      error: function(){ 
        console.log('ERROR');
      }
    })    
  }

}

