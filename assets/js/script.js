document.addEventListener('DOMContentLoaded', function() {
    
    // Fonction générique pour ajouter un nouvel élément
    function addDynamicItem(e) {
        const targetId = e.target.dataset.target;
        const container = document.getElementById(targetId);
        const template = document.getElementById(targetId + '-template');
        
        if (!container || !template) return;
        
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
    }

    // Fonction générique pour supprimer un élément
    function removeDynamicItem(e) {
        if (e.target.classList.contains('btn-delete-item')) {
            e.target.closest('.dynamic-item').remove();
        }
    }

    // Attacher les écouteurs d'événements
    const addButtons = document.querySelectorAll('.btn-add');
    addButtons.forEach(btn => {
        btn.addEventListener('click', addDynamicItem);
    });

    const formContainer = document.querySelector('form');
    if (formContainer) {
        formContainer.addEventListener('click', removeDynamicItem);
    }
});