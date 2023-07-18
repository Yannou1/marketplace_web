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

function deleteItem(itemId) {
      // Demander une confirmation avant de supprimer l'item
      if (confirm("Are you sure you want to delete this item?")) {
        // Effectuer la suppression de l'item en utilisant une requête AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_item.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            // Afficher une confirmation de suppression
            alert("The item has been successfully deleted.");

            // Actualiser la page pour afficher les changements
            location.reload();
          }
        };
        xhr.send("item_id=" + itemId);
      }
    }
  function showtype(type) {
  console.log(type)
  // Envoyer une requête AJAX pour obtenir les produits de la catégorie depuis le serveur
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      // Mettre à jour le contenu de la section avec les produits de la catégorie
      document.getElementById("type-items").innerHTML = this.responseText;
    }
  };
  xmlhttp.open("GET", "get_type.php?type=" + type, true);
  xmlhttp.send();
}

document.getElementById('sale_type').addEventListener('change', function() {
  var selectedValue = this.value;
  var additionalFields = document.querySelector('.additional-fields');
  var moovableFields = document.querySelector('.moovable-fields');

  if (selectedValue === 'auction') {
    additionalFields.style.display = 'block';
    setFieldsRequired(additionalFields, true);
    moovableFields.style.display = 'none';
    setFieldsRequired(moovableFields, false);
    resetFieldsValues(moovableFields);
    resetFieldsValidity(moovableFields);
  } else {
    additionalFields.style.display = 'none';
    setFieldsRequired(additionalFields, false);
    resetFieldsValues(additionalFields);
    resetFieldsValidity(additionalFields);
    moovableFields.style.display = 'block';
    setFieldsRequired(moovableFields, true);
  }
});

function setFieldsRequired(container, required) {
  var fields = container.querySelectorAll('input, select, textarea');
  fields.forEach(function(field) {
    field.required = required;
  });
}

function resetFieldsValues(container) {
  var fields = container.querySelectorAll('input, select, textarea');
  fields.forEach(function(field) {
    field.value = '';
  });
}

function resetFieldsValidity(container) {
  var fields = container.querySelectorAll('input, select, textarea');
  fields.forEach(function(field) {
    field.setCustomValidity('');
  });
}
document.addEventListener('DOMContentLoaded', function() {
            var sellerDiv = document.getElementById('seller-<?php echo $sellerId; ?>');
            var editLink = sellerDiv.querySelector('.edit-link');

            sellerDiv.addEventListener('mouseover', function() {
                editLink.style.display = 'block';
            });

            sellerDiv.addEventListener('mouseout', function() {
                editLink.style.display = 'none';
            });
        });
function acceptOffer(offerId) {
  // Change the status of the offer to "accepted".
  $.ajax({
    url: "/offers/" + offerId + "/accept",
    type: "POST",
    success: function() {
      // Update the table to reflect the change in status.
      var offerRow = $("#offer-" + offerId);
      offerRow.find(".status").text("Accepté");
    }
  });
}

function rejectOffer(offerId) {
  // Change the status of the offer to "rejected".
  $.ajax({
    url: "/offers/" + offerId + "/reject",
    type: "POST",
    success: function() {
      // Update the table to reflect the change in status.
      var offerRow = $("#offer-" + offerId);
      offerRow.find(".status").text("Rejeté");
    }
  });
}

function counterOffer(offerId) {
  // Create a new offer with the same item, same seller, but a different price.
  $.ajax({
    url: "/offers/" + offerId + "/counter",
    type: "POST",
    success: function(newOfferId) {
      // Update the table to reflect the new offer.
      var newOfferRow = $("#offer-" + newOfferId);
      newOfferRow.find(".price").text(newOffer.price);
      newOfferRow.find(".status").text("En attente");
      newOfferRow.find(".seller").text(newOffer.seller);
      newOfferRow.find(".receiver").text(newOffer.receiver);
    }
  });
}

