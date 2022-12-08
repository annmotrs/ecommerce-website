const selectSortType = document.querySelector('#select-sort');

selectSortType.addEventListener('change', ()=>{
  if(selectSortType.value === "price-min") {
    sortProductsAscPrice();
  }
  else if(selectSortType.value === "price-max") {
    sortProductsDescPrice();
  }
  else if(selectSortType.value === "new"){
    sortProductsNew();
  }
  else {
    sortProductsPopular();
  }
  
});

//Сортировки элементов производятся методом пузырька
//Сортировка по возрастанию цены
function sortProductsAscPrice() {
  let products = document.querySelector('.books__cards');

  for(let i = 0; i < products.children.length; i++) {
    for(let j = i; j < products.children.length; j++) {
      if(+products.children[i].dataset.price > +products.children[j].dataset.price) {
        let replacedNode = products.replaceChild(products.children[j], products.children[i]);
        insertAfter(replacedNode, products.children[i]);
      }
    }
  }
}

//Сортировка по убыванию цены
function sortProductsDescPrice() {
  let products = document.querySelector('.books__cards');

  for(let i = 0; i < products.children.length; i++) {
    for(let j = i; j < products.children.length; j++) {
      if(+products.children[i].dataset.price < +products.children[j].dataset.price) {
        let replacedNode = products.replaceChild(products.children[j], products.children[i]);
        insertAfter(replacedNode, products.children[i]);
      }
    }
  }
}

//Сортировка по дате добавления
function sortProductsNew() {
  let products = document.querySelector('.books__cards');

  for(let i = 0; i < products.children.length; i++) {
    for(let j = i; j < products.children.length; j++) {
      if(+products.children[i].dataset.id < +products.children[j].dataset.id) {
        let replacedNode = products.replaceChild(products.children[j], products.children[i]);
        insertAfter(replacedNode, products.children[i]);
      }
    }
  }
}

//Сортировка по популярности
function sortProductsPopular() {
  let products = document.querySelector('.books__cards');

  for(let i = 0; i < products.children.length; i++) {
    for(let j = i; j < products.children.length; j++) {
      if(+products.children[i].dataset.quantity < +products.children[j].dataset.quantity) {
        let replacedNode = products.replaceChild(products.children[j], products.children[i]);
        insertAfter(replacedNode, products.children[i]);
      }
    }
  }
}


function insertAfter(elem, refElem) {
  return refElem.parentNode.insertBefore(elem, refElem.nextSibling);
}