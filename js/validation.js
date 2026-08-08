function validateQuestion() {
    const input = document.getElementById("question");

    if (!input || input.value.trim() === "") {
        alert("La question ne doit pas etre vide");
        return false;
    }

    if (!input.value.trim().endsWith("?")) {
        alert("La question doit se terminer par un point d'interrogation (?)");
        return false;
    }

    return true;
}

function validateReponse() {
    const texte = document.getElementById("texte");

    if (!texte || texte.value.trim() === "") {
        alert("La reponse ne doit pas etre vide");
        return false;
    }

    if (texte.value.trim().length < 3) {
        alert("La reponse doit contenir au moins 3 caracteres");
        return false;
    }

    return true;
}

function validateMetierSelection() {
    const select = document.getElementById("id_metier");

    if (!select || select.value === "") {
        alert("Merci de selectionner un metier.");
        return false;
    }

    return true;
}

function validateQuizForm(totalQuestions) {
    const cards = document.querySelectorAll(".question-card");

    if (totalQuestions <= 0 || cards.length === 0) {
        alert("Aucune question disponible pour ce quiz.");
        return false;
    }

    let missingCount = 0;

    cards.forEach((card) => {
        const selected = card.querySelector('input[type="radio"]:checked');

        card.classList.remove("border", "border-danger");

        if (!selected) {
            missingCount++;
            card.classList.add("border", "border-danger");
        }
    });

    if (missingCount > 0) {
        alert("Merci de repondre a toutes les questions avant validation.");
        return false;
    }

    return true;
}

