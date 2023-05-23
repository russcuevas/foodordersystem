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


// DARK MODE JAVASCRIPT
const darkModePreference = localStorage.getItem('darkMode');

// Apply dark mode if the preference is set to 'true'
if (darkModePreference === 'true') {
  document.body.classList.add('dark');
}

let toggle = document.getElementById('toggle');

toggle.addEventListener('click', () => {
  console.log('Toggle button clicked');
  document.body.classList.toggle('dark');

  // Save the dark mode preference to local storage
  const isDarkMode = document.body.classList.contains('dark');
  localStorage.setItem('darkMode', isDarkMode);
});

 
 

// FOR LOADING PAGE
function loading() {
   document.querySelector('.loading').style.display = 'none';
}

function fadeOut() {
   setInterval(loading, 300);
}

window.onload = fadeOut;