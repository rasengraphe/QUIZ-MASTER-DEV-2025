// quiz_project/public/js/script.js

// Exemple : Validation de formulaire côté client (amélioration de l'expérience utilisateur)
const forms = document.querySelectorAll("form");
forms.forEach((form) => {
  form.addEventListener("submit", function (event) {
    let isValid = true;
    const inputs = form.querySelectorAll(
      "input[required], select[required], textarea[required]"
    );
    inputs.forEach((input) => {
      if (!input.value.trim()) {
        isValid = false;
        input.classList.add("error"); // Ajouter une classe d'erreur pour le style
      } else {
        input.classList.remove("error");
      }
    });

    if (!isValid) {
      event.preventDefault(); // Empêcher la soumission du formulaire
      alert("Veuillez remplir tous les champs obligatoires.");
    }
  });
});

// Exemple : Afficher/cacher des éléments (accordéon pour les règles du jeu)
const accordionHeaders = document.querySelectorAll(".accordion-header");
accordionHeaders.forEach((header) => {
  header.addEventListener("click", function () {
    this.classList.toggle("active");
    const panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    }
  });
});

// ... Autres fonctionnalités JavaScript (animations, chargement dynamique, etc.)
