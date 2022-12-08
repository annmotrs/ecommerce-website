const buttonsTaken = document.querySelectorAll('.orders__button-taken');

buttonsTaken.forEach(button => {

  const orderId = button.closest('.action').dataset.id;

  button.addEventListener('click', (event) =>{

    $.ajax({
      url: 'order-in-progress.php',
      type: 'POST',
      dataType: 'json',
      data: {
        order_id: orderId,
      },
      success: function(data){
        if(data === true) {
          const parentElement = button.closest('.orders__card');
          parentElement.remove(); //удаляем заказ со страницы
          if(document.querySelector('.orders__cards').children.length === 0) { //если заказов больше нет
            document.querySelector('.title').insertAdjacentHTML('afterend', '<div class="message">Заказов нет</div>'); //то показываем текст
            document.querySelector('.orders__cards').remove();
          }
        }
      },
      error: function(){ 
        console.log('ERROR');
      }
    })

  }); 
});