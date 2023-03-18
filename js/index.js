let modalPanelOn = false;

// Must have buy buttons event handlers
$("button[name='addToCart']").click(function () {
  addToCart(this);
});
$("button[name='buyNow']").click(function () {
  addToCart(this);
});

function addToCart(button) {
  if ($("input[name='link']").val() == "") {
    displayModalPanel("winking-face.webp", "You forgot about link");
  } else {
    var formData = new FormData();
    formData.append("product", "102");
    formData.append("link", $("input[name='link']").val());
    formData.append("quantity", $("input[name='quantity']").val());
    formData.append("language", $("select[name='language']").val());
    formData.append("addToCartFromMainPage", "yes");
    fetch("api/addToCart.php?v=" + Date.now(), {
      method: "POST",
      body: formData,
    });
    if (button.name == "buyNow") location.href = "cart.php";
    displayModalPanel("saluting-face.webp", "Added to cart", true);
  }
}

function displayModalPanel(imgName, message, destroyPrevious) {
  if (!modalPanelOn || destroyPrevious) {
    if (destroyPrevious && modalPanelOn)
      document.getElementsByClassName("modal-panel").item(0).remove();
    modalPanelOn = true;
    var panel = document.createElement("div");
    panel.classList.add("modal-panel");
    panel.innerHTML =
      "<img src='img/" + imgName + "' /> <p>" + message + "</p> ";
    document.body.appendChild(panel);
    setTimeout(function () {
      panel.classList.add("slide");
    }, 200);
    setTimeout(function () {
      panel.classList.remove("slide");
      modalPanelOn = false;
    }, 2500);
    setTimeout(function () {
      panel.remove();
    }, 2800);
  }
}


