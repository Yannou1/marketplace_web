document.addEventListener("DOMContentLoaded", function() {
  // Messages défilants
  var messages = document.querySelectorAll(".message");
  var duration = 5000; // Durée d'affichage de chaque message en millisecondes
  var currentIndex = 0;

  function showNextMessage() {
    messages[currentIndex].classList.remove("show");
    currentIndex = (currentIndex + 1) % messages.length;
    messages[currentIndex].classList.add("show");
  }

  messages[currentIndex].classList.add("show");
  setInterval(showNextMessage, duration);

  // Menu déroulant
  var dropdownMenu = document.querySelector(".dropdown-menu");
  var menuLink = document.querySelector(".nav-category a");

  function toggleDropdownMenu() {
    dropdownMenu.classList.toggle("show");
    var icon = menuLink.querySelector("img");
    if (dropdownMenu.classList.contains("show")) {
      icon.setAttribute("src", "logo/close.png");
    } else {
      icon.setAttribute("src", "logo/category.png");
    }
  }

  menuLink.addEventListener("click", toggleDropdownMenu);

  // Bannière avec défilement d'images
  var bannerImages = document.querySelectorAll('.banner-image');
  var prevButton = document.querySelector('.prev-button');
  var nextButton = document.querySelector('.next-button');
  var currentIndex = 0;
  var timer;

  function showImage(index) {
    bannerImages[currentIndex].classList.remove('active');
    bannerImages[index].classList.add('active');
    currentIndex = index;
  }

  function prevImage() {
    var index = (currentIndex - 1 + bannerImages.length) % bannerImages.length;
    showImage(index);
    resetTimer();
  }

  function nextImage() {
    var index = (currentIndex + 1) % bannerImages.length;
    showImage(index);
    resetTimer();
  }

  function resetTimer() {
    clearInterval(timer);
    timer = setInterval(nextImage, 10000); // Défilement automatique toutes les 10 secondes
  }

  prevButton.addEventListener('click', prevImage);
  nextButton.addEventListener('click', nextImage);

  resetTimer();

  var buyButtons = document.getElementsByClassName("buy-button");

  // Ajouter un gestionnaire d'événements pour chaque bouton
  for (var i = 0; i < buyButtons.length; i++) {
    buyButtons[i].addEventListener("click", function(event) {
      // Récupérer l'ID de l'item associé au bouton cliqué
      var itemId = event.target.getAttribute("data-item-id");

      // Envoyer une requête AJAX pour récupérer les détails de l'item
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          // Mettre à jour le contenu de la page avec les détails de l'item
          document.getElementById("page-content").innerHTML = this.responseText;
        }
      };
      xmlhttp.open("GET", "items_details.php?itemId=" + itemId, true);
      xmlhttp.send();
    });
  }
});

function showCategory(category) {
  console.log(category)
  // Envoyer une requête AJAX pour obtenir les produits de la catégorie depuis le serveur
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      // Mettre à jour le contenu de la section avec les produits de la catégorie
      document.getElementById("category-items").innerHTML = this.responseText;
    }
  };
  xmlhttp.open("GET", "get_items.php?category=" + category, true);
  xmlhttp.send();
}
