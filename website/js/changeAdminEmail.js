const form = document.querySelector('.admin-data__form');

form.addEventListener('submit', (event)=>{
  event.preventDefault();
  let isFormValid = form.checkValidity();
  if(!isFormValid) { //проверяем валидность формы
    form.reportValidity();
  }
  else {
    $.ajax({
      url: 'for-change-admin-email.php',
      type: 'POST',
      dataType: 'json',
      data: $('#form').serialize(),
      success: function(data) {
        if(data === true) {
          window.location.href = 'admin-personal-data.php'; //перенаправляем на страницу с личными данными
        }
        else {
          if(!document.querySelector('.admin-data__message')){ //если сообщения об ошибке нет, то показываем его
            document.querySelector('.admin-data__box-button').insertAdjacentHTML('beforebegin', '<div class="admin-data__message">Ошибка! Данный email уже зарегистрирован!</div>');
          }
        }
      },
      error: function(){ 
        console.log('ERROR');
      }
    })    
  }

  
})