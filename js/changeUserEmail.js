const form = document.querySelector('.change-user-data__form');

form.addEventListener('submit', (event)=>{
  event.preventDefault();
  let isFormValid = form.checkValidity();
  if(!isFormValid) { //проверяем валидность формы
    form.reportValidity();
  }
  else {
    $.ajax({
      url: 'for-change-email.php',
      type: 'POST',
      dataType: 'json',
      data: $('#form').serialize(),
      success: function(data) {
        if(data === true) {
          window.location.href = 'user-settings.php'; //перенаправляем в личный кабинет
        }
        else {
          if(!document.querySelector('.change-user-data__message')){ //если сообщения об ошибке нет, то показываем его
            document.querySelector('.change-user-data__box-button').insertAdjacentHTML('beforebegin', '<div class="change-user-data__message">Ошибка! Данный email уже зарегистрирован!</div>');
          }
        }
      },
      error: function(){ 
        console.log('ERROR');
      }
    })    
  }

  
})