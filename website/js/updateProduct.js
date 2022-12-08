const buttonAddAuthor = document.querySelector('.button-add-author');
const listAuthors = document.querySelector('.product-form__authors-list');

const inputAuthors = document.querySelector('.product-form__input-authors');
const authors = inputAuthors.value.split(', '); //осуществляем разбиение строки в массив по запятой

authors.forEach(author => { //добавляем пункты с авторами в список на странице
  listAuthors.insertAdjacentHTML('beforeend', `<li class="product-form__authors-item">${author}<i class="fa-solid fa-square-xmark product-form__icon-close"></li>`);
  deleteAuthor(listAuthors.lastElementChild);
});

buttonAddAuthor.addEventListener('click', ()=>{
  const inputAuthor = document.querySelector('#author');
  
  if(inputAuthor.value) {
    if(authors.length === 0) {
      listAuthors.firstElementChild.innerHTML = `${inputAuthor.value}<i class="fa-solid fa-square-xmark product-form__icon-close"></i>`; // слово "нет" заменяем на выбранного автора
      authors.push(inputAuthor.value); //добавляем автора в массив
      deleteAuthor(listAuthors.lastElementChild);
      const inputAuthors = document.querySelector('.product-form__input-authors');
      inputAuthors.value = authors.join(', '); //массив объединяем в строку через запятую
      inputAuthor.value = ''; //очищаем input
    }
    else if(!authors.includes(inputAuthor.value)){
      listAuthors.insertAdjacentHTML('beforeend', `<li class="product-form__authors-item">${inputAuthor.value}<i class="fa-solid fa-square-xmark product-form__icon-close"></li>`); //добавляем пункт с автором в список
      authors.push(inputAuthor.value); //добавляем автора в массив
      deleteAuthor(listAuthors.lastElementChild);
      const inputAuthors = document.querySelector('.product-form__input-authors');
      inputAuthors.value = authors.join(', '); //массив объединяем в строку через запятую
      inputAuthor.value = ''; //очищаем input
    }

  }  

});

// Удаление автора
function deleteAuthor(newElement){

  newElement.querySelector('.product-form__icon-close').addEventListener('click', ()=>{
    let index = authors.indexOf(newElement.textContent); //находим индекс автора в массиве, или получаем -1 если автор не найден
    if(index !== -1) {
      authors.splice(index, 1); //удаляем автора из массива по его индексу
    }
    newElement.remove(); //удаляем пункт с автором в списке
    inputAuthors.value = authors.join(', '); //массив объединяем в строку через запятую

    if(authors.length === 0) { //если в списке нет ничего
      listAuthors.innerHTML = '<li class="product-form__authors-item">нет</li>'; //то добавляем в список слово "нет"
    }

  });

}

const fileButtonText = document.querySelector('.product-form__file-button-text');
const inputFile = document.querySelector('.product-form__input-file')

//Изменяем текст в файловом поле
inputFile.addEventListener('change', function(){
  if(this.value) {
    fileButtonText.textContent = "Файл выбран";
  } 
  else { 
    fileButtonText.textContent = "Выберите файл";
  }
});