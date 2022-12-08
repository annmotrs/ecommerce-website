const dropdownMenuAll = document.querySelectorAll('.header__site-navigation-link + .header__dropdown-menu');
const menu = document.querySelector('#menu');
const siteNavigation = document.querySelector('.header__site-navigation');

dropdownMenuAll.forEach(dropdownMenu => {
  const siteNavigationLink = dropdownMenu.previousElementSibling;
  siteNavigationLink.addEventListener('click', function(event){
    event.preventDefault();
    dropdownMenu.classList.toggle('header__dropdown-menu--active');
  })
});

// //Закрытие выпадающего меню при клике в область вне него
document.addEventListener('click', event => {
  dropdownMenuAll.forEach(dropdownMenu => {
    closeDropdownMenu(dropdownMenu.previousElementSibling);
  });   
})

function closeDropdownMenu(siteNavigationLink) {
    if(siteNavigationLink.nextElementSibling.classList.contains('header__dropdown-menu--active')) {
    const withinBoundaries = event.composedPath().includes(siteNavigationLink);
    if(!withinBoundaries) {
      siteNavigationLink.nextElementSibling.classList.remove('header__dropdown-menu--active');
    }
  }
}

menu.addEventListener('click', function(event) {
  event.preventDefault();
  siteNavigation.classList.toggle('header__site-navigation--active');
  let icon = menu.querySelector('.header__admin-navigation-icon');
  if(!icon) {
    icon = menu.querySelector('.header__user-navigation-icon');
  }
  icon.classList.toggle('fa-bars');
  icon.classList.toggle('fa-close');
  document.body.classList.toggle('lock');
})