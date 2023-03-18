const stripe = Stripe(
  ""
);
checkStatus();
let elements;

document.getElementById("submitPromo").addEventListener("click", () => {
  let code = document.getElementById("promocode").value;
  let discount;
  if (code == "") return;
  (async () => {
    discount = await verifyDiscount(code);
    if (discount == "") $("#userMessage").text("Discount not found");
    else {
      let button = document.getElementById("submitPromo");
      button.disabled = true;
      document.getElementById("promocode").disabled = true;
      let priceText = document.getElementById("totalPriceText");
      let currency = priceText.innerHTML.slice(0, 1);
      let price = parseFloat(priceText.innerHTML.slice(1));
      if (discount[0].Amount != 0) {
        $("#discountText").text("- " + currency + discount[0].Amount);
        $("#userMessage").text(discount[0].Amount + "£ discount applied");
        priceText.innerHTML = currency + (price - discount[0].Amount);
      } else if (discount[0].Percent != 0) {
        let discountAmount = (price * discount[0].Percent) / 100;
        $("#discountText").text("- " + currency + discountAmount.toFixed(2));
        $("#userMessage").text(discount[0].Percent + "% discount applied");
        priceText.innerHTML = currency + (price - discountAmount).toFixed(2);
      }
    }
  })();
});

async function verifyDiscount(code) {
  const response = await fetch("api/getDiscount.php?discount=" + code + "&v=" + Date.now()); // Wait for the response
  const discount = await response.json(); // Parse the JSON response
  return discount;
}

document.getElementById("checkoutBtn").addEventListener("click", () => {
  if ($(".cart p").text() == "0") {
    $("#userMessage").text("Cart is empty");
  } else {
    window.scrollTo(0, 0);
    $("#checkoutModal").css("opacity", 1);
    $("#checkoutModal").css("z-index", "10");
    $(".cart-container").css("filter", "blur(10px)");
    $(".center-text-container").css("filter", "blur(10px)");
  }
});

document.getElementById("confirmBtn").addEventListener("click", () => {
  let email = $("#email").val();
  let name = $("#name").val();
  if (name != "" && email != "") {
    $("#errorMessage").text("");
    $("#confirmBtn").css("display", "none");
    $("#payment-form button#submit").css("display", "initial");
    $("#name").prop("disabled", true);
    $("#email").prop("disabled", true);
    initialize();
  } else {
    $("#errorMessage").text("Please fill in all the fields");
  }
});


document
  .querySelector("#payment-form")
  .addEventListener("submit", handleSubmit);

// Fetches a payment intent and captures the client secret
async function initialize() {
  var email = $("#email").val();
  let price = parseFloat($("#totalPriceText").text().slice(1));
  const data = {
    Price: price,
    Email: email,
  };
  const response = await fetch("api/checkout.php?v=" + Date.now(), {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  }).then((r) => r.json());

  const appearance = {
    theme: "night",
    labels: "floating",
  };
  elements = stripe.elements(response);
  const paymentElement = elements.create("payment");
  paymentElement.mount("#payment-element");
}

async function saveOrder() {
  let email = $("#email").val();
  let name = $("#name").val();

  let priceText = document.getElementById("totalPriceText");
  let price = parseFloat($("#totalPriceText").text().slice(1));
  let code = document.getElementById("promocode").value;

  let deviceType;
  if (
    /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
      navigator.userAgent
    )
  )
    deviceType = "Mobile";
  else deviceType = "Desktop";

  const data = {
    Name: name,
    Email: email,
    Price: price,
    Discount: code,
    Device: deviceType,
  };
  const response = await fetch("api/saveOrder.php?v=" + Date.now(), {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  }).then((r) => r.json());
}

async function handleSubmit(e) {
  e.preventDefault();
  setLoading(true);
  saveOrder();

  const { error } = await stripe.confirmPayment({
    elements,
    confirmParams: {
      // Make sure to change this to your payment completion page
      return_url: "https://boostyouraccount.com/cart.php",
    },
  });

  if (error.type === "card_error" || error.type === "validation_error") {
    showMessage(error.message);
  } else {
    showMessage("An unexpected error occurred.");
  }

  setLoading(false);
}

// Fetches the payment intent status after payment submission
async function checkStatus() {
  const clientSecret = new URLSearchParams(window.location.search).get(
    "payment_intent_client_secret"
  );

  if (!clientSecret) return;

  const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

  switch (paymentIntent.status) {
    case "succeeded":
      createOrder();
      createConfirmationPanel();
      clearCart();
      break;
    case "processing":
      createConfirmationPanel("processing");
      clearCart();
      break;
    case "requires_payment_method":
      createConfirmationPanel("error");
      break;
    default:
      createConfirmationPanel("error");
      break;
  }
}

function createOrder() {
  fetch("api/orderServices.php?v=" + Date.now());
}

async function createConfirmationPanel(status) {
  window.scrollTo(0, 0);
  let orderNumber = await getOrderNumber();
  let mainText = "Thank you for your order!";
  let baseText =
    "Your order number: " +
    orderNumber +
    "<br><br>Check your account, Boost Packages will be delivered soon!";
  if (status == "processing") {
    mainText = "Payment is processing!";
    baseText =
      "As soon as the payment is successful, you will receive a confirmation email and packages will be delivered to your account";
  } else if (status == "error") {
    mainText = "Payment declined!";
    baseText = "Something went wrong, please try again.";
  }

  $(".cart-container").css("filter", "blur(10px)");
  $(".center-text-container").css("filter", "blur(10px)");
  let panel = document.createElement("div");
  panel.classList.add("confirmation-panel");
  panel.innerHTML =
    '<p class="heading">' +
    mainText +
    "</p>" +
    '<img src="img/heart-hands.webp" alt="Heart hands emoji" />' +
    "<p>" +
    baseText +
    "</p>" +
    '<div class="return-home-btn"><p>Return to Home</p></div>';
  document.querySelector("body").appendChild(panel);
  $("div.return-home-btn").click(function () {
    location.href = "index.php";
  });
}

async function getOrderNumber() {
  const response = await fetch("api/getOrderNumber.php?v=" + Date.now()); // Wait for the response
  const orderNumber = await response.json(); // Parse the JSON response
  return orderNumber;
}

function clearCart() {
  var formData = new FormData();
  formData.append("removeAll", "yes");
  fetch("api/removeFromCart.php?v=" + Date.now(), {
    method: "POST",
    body: formData,
  });
}

// ------- UI helpers -------
function showMessage(messageText) {
  const messageContainer = document.querySelector("#payment-message");

  messageContainer.classList.remove("hidden");
  messageContainer.textContent = messageText;

  setTimeout(function () {
    messageContainer.classList.add("hidden");
    messageText.textContent = "";
  }, 4000);
}

// Show a spinner on payment submission
function setLoading(isLoading) {
  if (isLoading) {
    // Disable the button and show a spinner
    document.querySelector("#submit").disabled = true;
    document.querySelector("#spinner").classList.remove("hiddenn");
    document.querySelector("#button-text").classList.add("hiddenn");
  } else {
    document.querySelector("#submit").disabled = false;
    document.querySelector("#spinner").classList.add("hiddenn");
    document.querySelector("#button-text").classList.remove("hiddenn");
  }
}
