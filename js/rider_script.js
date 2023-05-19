// PROFILE ADMIN LOGIN AND UPDATE SELECTOR CLICKABLE
let profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active')
   navbar.classList.remove('active');
}

// NAVBAR MENU RESPONSIVE CLICKABLE IN ADMIN
let navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   profile.classList.remove('active');
}

// IF PROFILE IS ACTIVE NAVBAR REMOVE IN ADMIN
// IF NAVBAR IS ACTIVE PROFILE REMOVE IN ADMIN
window.onscroll = () =>{
   profile.classList.remove('active');
   navbar.classList.remove('active');
}