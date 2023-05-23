// PROFILE USER LOGIN AND UPDATE SELECTOR CLICKABLE

profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () => {
   profile.classList.toggle('active');
   navbar.classList.remove('active');
}

// NAVBAR MENU RESPONSIVE CLICKABLE IN USER
let navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () => {
   navbar.classList.toggle('active');
   profile.classList.remove('active');
}

// IF PROFILE IS ACTIVE NAVBAR REMOVE IN USER
// IF NAVBAR IS ACTIVE PROFILE REMOVE IN USER
window.onscroll = () => {
   profile.classList.remove('active');
   navbar.classList.remove('active');
}


// PRODUCT
document.querySelectorAll('input[type="number"]').forEach(numberInput => {
   numberInput.oninput = () => {
      if (numberInput.value.length > numberInput.maxLength) numberInput.value = numberInput.value.slice(0, numberInput.maxLength);
   };
});

// DARK MODE
let toggle = document.getElementById('toggle');
let isDarkMode = localStorage.getItem('darkMode') === 'true';

function toggleDarkMode() {
  console.log('Toggle button clicked');
  document.body.classList.toggle('dark');
  isDarkMode = !isDarkMode;

  if (isDarkMode) {
    toggle.classList.remove('fa-sun');
    toggle.classList.add('fa-moon');
  } else {
    toggle.classList.remove('fa-moon');
    toggle.classList.add('fa-sun');
  }

  localStorage.setItem('darkMode', isDarkMode);
}

if (isDarkMode) {
  document.body.classList.add('dark');
  toggle.classList.remove('fa-sun');
  toggle.classList.add('fa-moon');
}

toggle.addEventListener('click', toggleDarkMode);


 
 

// FOR LOADING PAGE
function loading() {
   document.querySelector('.loading').style.display = 'none';
}

function fadeOut() {
   setInterval(loading, 300);
}

window.onload = fadeOut;