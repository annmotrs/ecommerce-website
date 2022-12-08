const buttonsDeleteReview = document.querySelectorAll('.button-delete');
const dialog = document.querySelector('.dialog');
const buttonCancel = document.querySelector('.dialog__button-cancel');
const buttonDelete = document.querySelector('.dialog__button-delete');

let currentButton;
let reviewId;

buttonsDeleteReview.forEach(button => {

  button.addEventListener("click", (event) => {
    currentButton = event.currentTarget;
    reviewId = currentButton.closest('.user-reviews__actions').dataset.id;

    dialog.showModal(); //показываем модальное окно
    document.body.classList.add("scroll-lock"); //запрещаем скролл
    
 });
 
})

buttonCancel.addEventListener('click', () => {
  document.body.classList.remove("scroll-lock"); //разрешаем скролл
});

buttonDelete.addEventListener('click', () => {
  document.body.classList.remove("scroll-lock"); //разрешаем скролл
  if(reviewId !== undefined && currentButton !== undefined){
    deleteReview();
  }
});

function deleteReview() {

  $.ajax({
    url: 'delete-review.php',
    type: 'POST',
    data: {
      review_id: reviewId,
    },
    success: function(){
      const parentElement = currentButton.closest('.user-reviews__review'); 
      parentElement.remove(); //удаляем отзыв со страницы
    },
    error: function(){ 
      console.log('ERROR');
    }
  })

}
