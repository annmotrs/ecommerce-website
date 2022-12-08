const buttons = document.querySelectorAll('.products__button-update-sale');

buttons.forEach(button => {

  const productId = button.closest('.action').dataset.id;

  button.addEventListener('click', () =>{
    if(button.classList.contains('products__button-add-sale')){

      $.ajax({
        url: 'add-sale-product.php',
        type: 'POST',
        dataType: 'json',
        data: {
          product_id: productId,
        },
        success: function(data){
          if(data === true) {
            button.classList.replace('products__button-add-sale', 'products__button-delete-sale');
            button.textContent = "Убрать из продажи"; //меняем текст кнопки
            const parentElement = button.closest('.products__card');
            const status = parentElement.querySelector('.products__card-status-value');
            status.classList.replace('products__card-value--color_red', 'products__card-value--color_green');
            status.textContent = "в продаже"; //меняем статус
          }
        },
        error: function(){ 
          console.log('ERROR');
        }
      }); 

    }
    else{

      $.ajax({
        url: 'delete-sale-product.php',
        type: 'POST',
        dataType: 'json',
        data: {
          product_id: productId,
        },
        success: function(data){
          if(data === true) {
            button.classList.replace('products__button-delete-sale', 'products__button-add-sale');
            button.textContent = "Добавить в продажу"; //меняем текст кнопки
            const parentElement = button.closest('.products__card');
            const status = parentElement.querySelector('.products__card-status-value');
            status.classList.replace('products__card-value--color_green', 'products__card-value--color_red');
            status.textContent = "не в продаже"; //меняем статус
          }
        },
        error: function(){ 
          console.log('ERROR');
        }
      }); 
      
    }
  });

});