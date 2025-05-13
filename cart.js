document.addEventListener('DOMContentLoaded', function () {
  // 1. CART MANAGEMENT
  let cart = JSON.parse(localStorage.getItem('cart')) || [];

  function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCart();
  }

  function loadCart() {
    const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
    let output = '';
    let total = 0;

    cartItems.forEach((item, index) => {
      output += `
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
          <div>
            <h5>${item.name}</h5>
            <p>Quantité: ${item.quantity}</p>
          </div>
          <div class="text-end">
            <p><strong>${item.price * item.quantity} DA</strong></p>
            <button class="btn btn-danger btn-sm" onclick="removeItem(${index})">Supprimer</button>
          </div>
        </div>
      `;
      total += item.price * item.quantity;
    });

    document.getElementById('cart-items').innerHTML = output;
    document.getElementById('total').innerText = total;
  }

  // 2. CHECKOUT PROCESS
  function processCheckout(e) {
    e.preventDefault();

    const fullName = document.getElementById('fullName').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const wilaya = document.getElementById('wilaya').value;
    const submitBtn = document.querySelector('#orderForm button[type="submit"]');

    if (cart.length === 0) {
      alert("Votre panier est vide.");
      return;
    }

    try {
      submitBtn.disabled = true;
      submitBtn.textContent = "Envoi en cours...";

      let orderDetails = cart.map(item => 
        `${item.name} (${item.quantity}x) - ${item.price * item.quantity} DA`
      ).join('\n');

      let total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

      const formData = new FormData();
      formData.append('fullName', fullName);
      formData.append('email', email);
      formData.append('phone', phone);
      formData.append('wilaya', wilaya);
      formData.append('orderDetails', orderDetails);
      formData.append('total', total + ' DA');

      fetch('send_order.php', {
        method: 'POST',
        body: formData
      })
        .then(res => res.text())
        .then(response => {
          alert("Commande envoyée avec succès !");
          localStorage.removeItem('cart');
          window.location.href = 'index.html';
        })
        .catch(err => {
          console.error("Erreur lors de l'envoi :", err);
          alert("Erreur lors de l'envoi de votre commande. Réessayez plus tard.");
        });
    } catch (error) {
      console.error("Checkout error:", error);
      alert("Une erreur est survenue. Veuillez réessayer.");
    } finally {
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.textContent = "Confirmer la commande";
      }
    }
  }

  // 3. INITIALIZE APPLICATION
  loadCart();

  // Add event listener for the order form submission
  document.getElementById('orderForm').addEventListener('submit', processCheckout);

  // Function to remove items from cart
  window.removeItem = function(index) {
    cart.splice(index, 1);
    saveCart();
  };
});