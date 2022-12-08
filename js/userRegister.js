var element = document.getElementById('phone');
var maskOptions = {
  mask: '+{7} (000) 000-00-00'
};
var mask = IMask(element, maskOptions);

const form = document.querySelector('.user-register__form');

form.addEventListener('submit', (event)=>{
  event.preventDefault();
  let isFormValid = form.checkValidity();
  if(!isFormValid) { //проверяем валидность формы
    form.reportValidity();
  }
  else {
    $.ajax({
      url: 'for-user-register.php',
      type: 'POST',
      dataType: 'json',
      data: $('#form').serialize(),
      success: function(data){
        if(data === true) {
          window.location.href = 'user-login.php'; //перенаправляем на страницу авторизации пользователя
        }
        else {
          if(!document.querySelector('.user-register__message')){ //если сообщения об ошибке нет, то показываем его
            document.querySelector('.user-register__input-groups').insertAdjacentHTML('afterend', '<div class="user-register__message">Ошибка! Данный email уже зарегистрирован!</div>');
          }
        }
      },
      error: function(){ 
        console.log('ERROR');
      }
    })    
  }

  
})