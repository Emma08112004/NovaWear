document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function () {
        let productId = this.getAttribute('data-id');
        let size = document.querySelector(`#size-${productId}`).value;

        if (!size) {
            alert('Veuillez sélectionner une taille avant d\'ajouter au panier.');
            return;
        }

        fetch(`/ajout-panier/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ taille: size })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
        })
        .catch(error => console.error('Erreur:', error));
    });
});