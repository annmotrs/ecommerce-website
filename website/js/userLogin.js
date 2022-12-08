const form = document.querySelector('.user-login__form');

form.addEventListener('submit', (event)=>{
  event.preventDefault();
  let isFormValid = form.checkValidity();
  if(!isFormValid) { //проверяем валидность формы
    form.reportValidity();
  }
  else {
    $.ajax({
      url: 'for-user-login.php',
      type: 'POST',
      dataType: 'json',
      data: $('#form').serialize(),
      success: function(data){
        if(data === true) {
          window.location.href = 'index.php'; //перенаправляем на главную страницу интернет-магазина
        }
        else {
          if(!document.querySelector('.user-login__message')){ //если сообщения об ошибке нет, то показываем его
            document.querySelector('.user-login__input-groups').insertAdjacentHTML('afterend', '<div class="user-login__message">Ошибка! Некорректный email или пароль!</div>');
          }
        }
      },
      error: function(){ 
        console.log('ERROR');
      }
    })    
  }

  
})