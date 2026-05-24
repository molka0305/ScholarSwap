document.addEventListener("DOMContentLoaded", function () {
    console.log("quiz.js chargé");

    // Récupère le nom du fichier actuel
    const page = window.location.pathname.split("/").pop().toLowerCase();
    console.log("Fichier détecté :", page);

    // SYSTEME DE SECURITE : Associe la page au bon quiz peu importe le nom du fichier (tiret ou point)
    let quizKey = "";
    if (page.includes("commencer")) {
        quizKey = "commencer-quiz.html";
    } else if (page.includes("continuer")) {
        quizKey = "continuer-quiz.html";
    } else if (page.includes("refaire")) {
        quizKey = "refaire-quiz.html";
    }

    const quizzes = {
        "commencer-quiz.html": {
            title: "Algorithmique JS",
            level: "Difficile",
            levelClass: "hard",
            intro: "C'est votre première tentative. Prenez votre temps !",
            questions: [
                {
                    question: "Quelle est la complexité d'une recherche binaire ?",
                    options: ["O(n)", "O(log n)", "O(n²)"],
                    correct: 1
                },
                {
                    question: "À quoi sert une boucle while ?",
                    options: [
                        "Répéter tant qu'une condition est vraie",
                        "Créer une fonction",
                        "Déclarer une variable"
                    ],
                    correct: 0
                },
                {
                    question: "Comment déclare-t-on un tableau en JavaScript ?",
                    options: ["{}", "()", "[]"],
                    correct: 2
                },
                {
                    question: "Quelle méthode ajoute un élément à la fin d'un tableau ?",
                    options: ["pop()", "push()", "shift()"],
                    correct: 1
                },
                {
                    question: "Que donne l'opérateur modulo % ?",
                    options: [
                        "Le quotient",
                        "Le reste de la division",
                        "La multiplication"
                    ],
                    correct: 1
                }
            ]
        },

        "continuer-quiz.html": {
            title: "Sélecteurs CSS3",
            level: "Intermédiaire",
            levelClass: "medium",
            intro: "Reprise de votre session.",
            questions: [
                {
                    question: "Quel sélecteur cible le premier enfant d'un élément ?",
                    options: [":first-child", ":last-child", ":nth-child(1)"],
                    correct: 0
                },
                {
                    question: "Quel sélecteur cible un identifiant ?",
                    options: [".titre", "#titre", "titre"],
                    correct: 1
                },
                {
                    question: "Quel sélecteur cible une classe ?",
                    options: ["#menu", ".menu", "menu"],
                    correct: 1
                },
                {
                    question: "Quelle propriété change la couleur du texte ?",
                    options: ["background", "font-family", "color"],
                    correct: 2
                },
                {
                    question: "Quelle propriété ajoute une ombre ?",
                    options: ["box-shadow", "border-shadow", "shadow"],
                    correct: 0
                }
            ]
        },

        "refaire-quiz.html": {
            title: "Bases du HTML5",
            level: "Facile",
            levelClass: "easy",
            intro: "Votre meilleur score actuel : 14/15",
            questions: [
                {
                    question: "À quoi sert la balise <nav> ?",
                    options: [
                        "Contenir des liens de navigation",
                        "Créer un menu déroulant",
                        "Définir le pied de page"
                    ],
                    correct: 0
                },
                {
                    question: "Quelle balise permet d'insérer une image ?",
                    options: ["<picture>", "<img>", "<figure>"],
                    correct: 1
                },
                {
                    question: "Quel attribut définit le lien d'une balise <a> ?",
                    options: ["src", "link", "href"],
                    correct: 2
                },
                {
                    question: "Quelle balise permet de créer un formulaire ?",
                    options: ["<form>", "<input>", "<button>"],
                    correct: 0
                },
                {
                    question: "Quel élément indique que la page est en HTML5 ?",
                    options: ["<!DOCTYPE html>", "<html5>", "<doctype5>"],
                    correct: 0
                }
            ]
        }
    };

    const quiz = quizzes[quizKey];

    if (!quiz) {
        console.warn("Aucun quiz correspondant trouvé pour :", page);
        return;
    }

    const quizContainer = document.querySelector(".quiz-container");
    const quizHeader = document.querySelector(".quiz-header");
    const badge = document.querySelector(".quiz-badge");
    const title = document.querySelector(".quiz-header h2");
    const questionBox = document.querySelector(".question-box");
    let questionMeta = document.querySelector(".q-meta");
    const questionTitle = document.querySelector(".question-box h3");
    const optionsContainer = document.querySelector(".options");
    const validateBtn = document.querySelector(".quiz-footer .btn-primary");
    const quitBtn = document.querySelector(".quiz-footer .btn-main");
    let progressBar = document.querySelector(".progress-bar");

    if (!quizContainer || !quizHeader || !badge || !title || !questionBox || !questionTitle || !optionsContainer || !validateBtn) {
        console.error("Structure HTML incomplète détectée.");
        return;
    }

    if (!questionMeta) {
        questionMeta = document.createElement("div");
        questionMeta.className = "q-meta";
        questionBox.insertBefore(questionMeta, questionTitle);
    }

    if (!progressBar) {
        const progressContainer = document.createElement("div");
        progressContainer.className = "progress-container";
        progressBar = document.createElement("div");
        progressBar.className = "progress-bar";
        progressContainer.appendChild(progressBar);
        quizHeader.appendChild(progressContainer);
    }

    const headerText = document.querySelector(".quiz-header p");
    if (headerText) {
        headerText.textContent = quiz.intro;
    }

    if (quitBtn) {
        quitBtn.onclick = function () {
            window.location.href = "quiz.php";
        };
    }

    badge.textContent = quiz.level;
    badge.className = "quiz-badge " + quiz.levelClass;
    title.textContent = quiz.title;

    let currentIndex = 0;
    let selectedIndex = null;
    let score = 0;
    let validated = false;

    function afficherQuestion() {
        const q = quiz.questions[currentIndex];
        selectedIndex = null;
        validated = false;

        questionMeta.textContent = "Question " + (currentIndex + 1) + " / " + quiz.questions.length;
        questionTitle.textContent = q.question;

        const progress = ((currentIndex + 1) / quiz.questions.length) * 100;
        progressBar.style.width = progress + "%";

        optionsContainer.innerHTML = "";

        q.options.forEach(function (option, index) {
            const btn = document.createElement("button");
            btn.type = "button";
            btn.className = "opt";
            btn.textContent = option;

            btn.addEventListener("click", function () {
                if (validated) return;

                document.querySelectorAll(".opt").forEach(function (b) {
                    b.classList.remove("active-option");
                });

                btn.classList.add("active-option");
                selectedIndex = index;
                validateBtn.disabled = false;
            });

            optionsContainer.appendChild(btn);
        });

        validateBtn.textContent = "Valider la réponse";
        validateBtn.disabled = true;
    }

    function validerReponse() {
        if (selectedIndex === null) {
            alert("Choisissez une réponse avant de valider.");
            return;
        }

        const q = quiz.questions[currentIndex];
        const buttons = document.querySelectorAll(".opt");
        validated = true;

        buttons.forEach(function (btn) {
            btn.style.pointerEvents = "none";
        });

        if (selectedIndex === q.correct) {
            buttons[selectedIndex].classList.add("correct");
            score++;
        } else {
            buttons[selectedIndex].classList.add("wrong");
            buttons[q.correct].classList.add("correct");
        }

        if (currentIndex === quiz.questions.length - 1) {
            validateBtn.textContent = "Terminer le quiz";
        } else {
            validateBtn.textContent = "Question suivante";
        }
    }

    function questionSuivante() {
        currentIndex++;
        if (currentIndex < quiz.questions.length) {
            afficherQuestion();
        } else {
            afficherResultat();
        }
    }

    function afficherResultat() {
        quizContainer.innerHTML = `
            <div class="quiz-header" style="text-align:center; padding: 45px 20px;">
                <div style="font-size:60px; margin-bottom:15px;">🎉</div>
                <h2>Quiz terminé !</h2>
                <p>Votre score final est de :</p>
                <div style="font-size:42px; font-weight:800; color:#5BCAF4; margin:20px 0;">
                    ${score} / ${quiz.questions.length}
                </div>
                <button type="button" class="btn-primary" onclick="window.location.href='quiz.php'" style="padding: 10px 25px; font-size: 16px;">
                    Retour aux quiz
                </button>
            </div>
        `;
    }

    validateBtn.addEventListener("click", function () {
        if (!validated) {
            validerReponse();
        } else {
            questionSuivante();
        }
    });

    afficherQuestion();
});