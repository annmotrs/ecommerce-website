const buttonsPublish = document.querySelectorAll('.reviews__button-publish');
const buttonsCancel = document.querySelectorAll('.reviews__button-cancel');

buttonsPublish.forEach(button => {

  const reviewId = button.closest('.action').dataset.id;

  button.addEventListener('click', (event) =>{

    $.ajax({
      url: 'publish-review.php',
      type: 'POST',
      dataType: 'json',
      data: {
        review_id: reviewId,
      },
      success: function(data){
        if(data === true) {
          const parentElement = button.closest('.reviews__card');
          parentElement.remove(); //удаляем отзыв со страницы
          if(document.querySelector('.reviews__cards').children.length === 0) { //если отзывов больше нет
            document.querySelector('.title').insertAdjacentHTML('afterend', '<div class="message">Отзывов нет</div>'); //то показываем текст
            document.querySelector('.reviews__cards').remove();
          }
        }
      },
      error: function(){ 
        console.log('ERROR');
      }
    })

  }); 
});

buttonsCancel.forEach(button => {

  const reviewId = button.closest('.action').dataset.id;

  button.addEventListener('click', (event) =>{

    $.ajax({
      url: 'cancel-review.php',
      type: 'POST',
      dataType: 'json',
      data: {
        review_id: reviewId,
      },
      success: function(data){
        if(data === true) {
          const parentElement = button.closest('.reviews__card');
          parentElement.remove();
          if(document.querySelector('.reviews__cards').children.length === 0) {
            document.querySelector('.title').insertAdjacentHTML('afterend', '<div class="message">Отзывов нет</div>');
            document.querySelector('.reviews__cards').remove();
          }
        }
      },
      error: function(){ 
        console.log('ERROR');
      }
    })

  }); 

});