const form = document.querySelector('.admin-register__form');

form.addEventListener('submit', (event)=>{
  event.preventDefault();
  let isFormValid = form.checkValidity();
  if(!isFormValid) { //проверяем валидность формы
    form.reportValidity();
  }
  else {
    $.ajax({
      url: 'for-admin-register.php',
      type: 'POST',
      dataType: 'json',
      data: $('#form').serialize(),
      success: function(data) {
        if(data) {
          form.remove(); //удаляем форму
          document.querySelector('.admin-register__title').innerHTML = `Регистрация администратора прошла успешно.<br>ID администратора: ${data}.`; //показываем сообщение об успехе 
        }
        else {
          if(!document.querySelector('.admin-register__message')){ //если сообщения об ошибке нет, то показываем его
            document.querySelector('.admin-register__input-groups').insertAdjacentHTML('afterend', '<div class="admin-register__message">Ошибка! Данный email уже зарегистрирован!</div>');
          }
        }
      },
      error: function(){ 
        console.log('ERROR');
      }
    })    
  }

  
})