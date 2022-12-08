const form = document.querySelector('.admin-login__form');

form.addEventListener('submit', (event)=>{
  event.preventDefault();
  let isFormValid = form.checkValidity(); 
  if(!isFormValid) { //проверяем валидность формы
    form.reportValidity();
  }
  else {
    $.ajax({
      url: 'for-admin-login.php',
      type: 'POST',
      dataType: 'json',
      data: $('#form').serialize(),
      success: function(data) {
        if(data === true) {
          window.location.href = 'dashboard.php'; //перенаправляем на главную страницу администратора
        }
        else {
          if(!document.querySelector('.admin-login__message')){ //если сообщения об ошибке нет, то показываем его
            document.querySelector('.admin-login__input-groups').insertAdjacentHTML('afterend', '<div class="admin-login__message">Ошибка! Некорректный email или пароль!</div>');
          }
        }
      },
      error: function(){ 
        console.log('ERROR');
      }
    })    
  }

  
})