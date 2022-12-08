const selectStatusAll = document.querySelectorAll('.select-status');

selectStatusAll.forEach(select => {

  const orderId = select.closest('.action').dataset.id;

  select.addEventListener('change', (event) =>{

    const status = select.value;

    $.ajax({
      url: 'update-status-order.php',
      type: 'POST',
      dataType: 'json',
      data: {
        order_id: orderId,
        status: status
      },
      success: function(data){
        if(data === true) {
          if(status === "выполнен") {
            const parentElement = select.closest('.orders__card');
            parentElement.remove(); //удаляем заказ со страницы
            if(document.querySelector('.orders__cards').children.length === 0) { //если заказов больше нет
              document.querySelector('.title').insertAdjacentHTML('afterend', '<div class="message">Заказов нет</div>'); //то показываем текст
              document.querySelector('.orders__cards').remove();
            }
          }  
        }
      },
      error: function(){ 
        console.log('ERROR');
      }
    }) 

  }); 
});