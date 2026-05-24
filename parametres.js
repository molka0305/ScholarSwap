function showToast(message) {

    const toast = document.getElementById("toast");

    toast.innerText = message;

    toast.classList.add("show");

    setTimeout(() => {

        toast.classList.remove("show");

    }, 3000);
}

// BOUTON MOT DE PASSE
function modifierMotDePasse() {

    showToast("Mot de passe modifié avec succès !");
}

// BOUTON ENREGISTRER
function enregistrer() {

    showToast("Modifications enregistrées !");
}