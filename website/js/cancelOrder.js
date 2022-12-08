const buttonsCancelOrder = document.querySelectorAll('.button');
const dialog = document.querySelector('.dialog');
const buttonCancel = document.querySelector('.dialog__button-cancel');
const buttonDelete = document.querySelector('.dialog__button-delete');

let currentButton;
let orderId;

buttonsCancelOrder.forEach(button => {

  button.addEventListener("click", (event) => {
    currentButton = event.currentTarget;
    orderId = currentButton.closest('.orders__actions').dataset.id;
    
    dialog.showModal(); //показываем модальное окно
    document.body.classList.add("scroll-lock"); //запрещаем скролл
    
  });
 
});

buttonCancel.addEventListener('click', () => {
  document.body.classList.remove("scroll-lock"); //разрешаем скролл
});

buttonDelete.addEventListener('click', () => {
  document.body.classList.remove("scroll-lock"); //разрешаем скролл
  if(orderId !== undefined && currentButton !== undefined){
    cancelOrder();
  }
});

function cancelOrder() {

  $.ajax({
    url: 'update-status-order.php',
    type: 'POST',
    dataType: 'json',
    data: {
      order_id: orderId,
    },
    success: function(data){
      const parentElement = currentButton.closest('.orders__order');
      const statusElement = parentElement.querySelector('.orders__info-status');
      statusElement.textContent = data; //обновляем статус
      statusElement.classList.add('orders__info-status--fail'); 
      currentButton.remove(); //удаляем кнопку отмены заказа
    },
    error: function(){ 
      console.log('ERROR');
    }
  })

}

