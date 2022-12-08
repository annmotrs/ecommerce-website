const buttons = document.querySelectorAll('.orders__button-products');

//Показываем и скрываем товары по нажатию на кнопку
buttons.forEach(button => {
    button.addEventListener("click", (event) => {
      const products = event.target.closest('.orders__order').lastElementChild;  
      products.classList.toggle('orders__products--active');
      if(event.target.textContent === "Показать товары") {
        event.target.textContent = "Скрыть товары";
      } else {
        event.target.textContent = "Показать товары";
      }
      
   });
})
