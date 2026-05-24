document.addEventListener('DOMContentLoaded', () => {
    
    console.log("ScholarSwap : JavaScript chargé !");

    // 1. GESTION DES LIKES (Page Accueil)
    const likeButtons = document.querySelectorAll('.action-btn');
    likeButtons.forEach(button => {
        if (button.textContent.includes('❤️')) {
            button.addEventListener('click', function() {
                let parts = this.textContent.trim().split(/\s+/);
                let count = parseInt(parts[1]) || 0;

                if (this.classList.contains('liked')) {
                    this.classList.remove('liked');
                    this.style.color = '#5f6368'; 
                    this.textContent = `❤️ ${count - 1}`;
                } else {
                    this.classList.add('liked');
                    this.style.color = '#e74c3c'; 
                    this.textContent = `❤️ ${count + 1}`;
                }
            });
        }
    });

    // 2. MOTEUR DE RECHERCHE UNIVERSEL (Toutes les pages)
    const searchInput = document.querySelector('.search-container input');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.resource-card, .subject-card, .quiz-card, .group-card');

            cards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const descElem = card.querySelector('p');
                const description = descElem ? descElem.textContent.toLowerCase() : "";

                if (title.includes(term) || description.includes(term)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // 3. EFFET SUR LA SIDEBAR (Menu latéral)
    const menuItems = document.querySelectorAll('.sidebar li');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            menuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // 4. LOGIQUE DE CONNEXION (Page Login)
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault(); 
            console.log("Connexion réussie !");
            window.location.href = 'index.html';
        });
    }

    // 5. CLIC SUR LES MATIÈRES (Page Matières)
    const subjectCards = document.querySelectorAll('.subject-card');
    subjectCards.forEach(card => {
        card.addEventListener('click', () => {
            const subjectName = card.querySelector('h3').textContent;
            alert("Ouverture du dossier : " + subjectName);
        });
    });

    // 6. CLIC SUR LES QUIZ (Page Quiz)
    const quizButtons = document.querySelectorAll('.start-btn');
    quizButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation(); 
            const quizTitle = btn.parentElement.querySelector('h3').textContent;
            alert("Lancement du quiz : " + quizTitle + " \nBonne chance ! 🍀");
        });
    });

    // 7. CLIC REJOINDRE GROUPE (Page Groupes d'étude)
    const joinButtons = document.querySelectorAll('.join-btn:not(.joined)');
    joinButtons.forEach(btn => {
        btn.addEventListener