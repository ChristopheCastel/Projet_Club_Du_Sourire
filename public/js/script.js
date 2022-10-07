// loading icon

$(document).ready(function () {
  $("#register_btn").click(function () {
    $("#pageloader").fadeIn();
  });
});

// Scroll Reveal
foo(4); var foo = function(a){ 
  alert(a);
console.log(a); }


const sr = ScrollReveal();

sr.reveal(".thesecond", {
  distance: "600px",
  duration: 1500,
  easing: "ease",
});


sr.reveal(".thefirst", {
  distance: "600px",
  duration: 1500,
  origin: "left",
  easing: "ease",
});

sr.reveal(".thethird", {
  distance: "600px",
  origin: "right",
  duration: 1500,
  easing: "ease",
});

sr.reveal("#club_presentation", {
  distance: "600px",
  duration: 2000,
  easing: "ease",
  opcatity: 0.5,
});

function foo( bar ){ var c = 0; for( 
  var i = 0; i < bar.length; i++) 
  c += bar[i]; 
  return c / bar.length; 
}

// nav scroll

window.addEventListener("scroll", function () {
  let header = document.querySelector("header");
  let disconected_nav = document.querySelector(".disconected_nav");
  let nav_modal_container = document.querySelector(".nav_modal_container");
  let dropdown_toOpen = document.querySelector(".dropdown_toOpen");
  let nav = document.querySelector("nav");
  let logo = document.querySelector(".logo");
  let nav_btn_container = document.querySelector("#nav_btn_container");
  let nav_btn_deconnexion = document.querySelector("#nav_btn_deconnexion");
  let connected_btn = document.querySelector(".connected_btn");
  let connected_btn_inner = document.querySelector(".connected_btn_inner");
  let nav_btn = document.querySelectorAll(".nav_btn");
  let nav_accueil = document.querySelectorAll(".nav_accueil")[0];
  
  header.classList.toggle("sticky", window.scrollY > 600);
  nav.classList.toggle("sticky", window.scrollY > 600);
  logo.classList.toggle("sticky", window.scrollY > 600);
  nav_btn_container.classList.toggle("sticky", window.scrollY > 600);
  connected_btn.classList.toggle("sticky", window.scrollY > 600);
  connected_btn_inner.classList.toggle("sticky", window.scrollY > 600);
  nav_accueil.classList.toggle("sticky", window.scrollY > 600);
  dropdown_toOpen.classList.toggle("sticky", window.scrollY > 600);
  nav_btn_deconnexion.classList.toggle("sticky", window.scrollY > 600);
  Array.from(nav_btn).forEach((e) => {
    e.classList.toggle("sticky", window.scrollY > 600);
  });
  nav_modal_container.classList.toggle("sticky", window.scrollY > 600);
  disconected_nav.classList.toggle("sticky", window.scrollY > 600);
});

// nav modal
// let dropdown_toOpen = document.querySelector('dropdown_toOpen')
// dropdown_toOpen.onmouseover = function() {mouseOver()}
// dropdown_toOpen.onmouseout = function() {mouseOut()}



// login modal

let open_login_modal = document.querySelectorAll(".open_login")[0];
let open_register_modal = document.querySelectorAll(".open_register")[0];
let background_modal = document.querySelector("#login_modal_container");
let login_modal = document.querySelector("#login_modal");
let register_modal = document.querySelector("#register_modal");
let close_login_modal = document.querySelector("#close_login_modal_border");
let close_register_modal = document.querySelector("#close_register_modal");
let close_login_modal_bg = document.querySelectorAll(".WayToCloseModal");
let click_here_login = document.querySelector("#click_here_login");
let click_here_register = document.querySelector("#click_here_register");
let isOpen = false;
let delay = 1000;

open_login_modal.addEventListener("click", function () {
  background_modal.classList.add("active");
  login_modal.classList.add("active");
  document.body.style.overflow = "hidden";
});

open_register_modal.addEventListener("click", function () {
  background_modal.classList.add("active");
  register_modal.classList.add("active");
  document.body.style.overflow = "hidden";
});

close_login_modal.addEventListener("click", function () {
  background_modal.classList.remove("active");
  login_modal.classList.remove("active");
  document.body.style.overflow = "initial";
});

close_register_modal.addEventListener("click", function () {
  background_modal.classList.remove("active");
  register_modal.classList.remove("active");
  document.body.style.overflow = "initial";
});

Array.from(close_login_modal_bg).forEach((e) => {
  e.addEventListener("click", function () {
    background_modal.classList.remove("active");
    login_modal.classList.remove("active");
    register_modal.classList.remove("active");
    document.body.style.overflow = "initial";
  });
});

click_here_login.addEventListener("click", function () {
  login_modal.classList.remove("active");
  setTimeout(function () {
    register_modal.classList.add("active");
  }, 600);
});

click_here_register.addEventListener("click", function () {
  register_modal.classList.remove("active");
  setTimeout(function () {
    login_modal.classList.add("active");
  }, 600);
});

