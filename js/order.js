const button = document.querySelector('.button');
const iconsMenu = document.querySelectorAll('.header__user-navigation-item');

if(button !== null) {
  button.addEventListener('click', ()=>{
  
  $.ajax({
    url: 'add-order.php',
    type: 'POST',
    dataType: 'json',
    success: function(data){
      const order = document.querySelector('.order');
      //показываем сообщение, что заказ успешно оформлен
      order.insertAdjacentHTML('afterend', `<section class="order-success">
                                              <div class="order-success__wrapper">
                                                <h1 class="order-success__title">Спасибо за заказ!</h1> 
                                                <p class="order-success__text">Ваш заказ успешно оформлен. Номер вашего заказа: <span class="order-success__order-num">${data}</span>.</p>
                                                <p class="order-success__text">Просматривать состояние заказов Вы можете на <a href="active-orders.php" class="order-success__link">странице активных заказов</a> в Личном кабинете.</p>
                                                <i class="fa-regular fa-circle-check order-success__icon"></i>
                                              </div>
                                            </section>`);
      order.remove();      
      iconsMenu[1].dataset.countCart = 0; //обнуляем счетчик у иконки корзины в меню                                   
    },
    error: function(){ 
      console.log('ERROR');
    }
  })

  });
}
