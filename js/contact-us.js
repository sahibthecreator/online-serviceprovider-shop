function SendMessage() {
  if ($(".boost-btn").get(0).classList.contains("disabled")) return;
  var fullName = $('input[name="fullName"]').get(0);
  var email = $('input[name="email"]').get(0);
  var orderNumber = $('input[name="orderNumber"]').get(0);
  var message = $('textarea[name="message"]').get(0);
  if (fullName.value != "" && email.value != "" && message.value != "") {
    var data = {
      FullName: fullName.value,
      Email: email.value,
      OrderNumber: orderNumber.value,
      Message: message.value,
    };
    $(".boost-btn").get(0).classList.add("disabled");
    fetch("api/saveMessage.php?v=" + Date.now(), {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    }).then((response) => {
      $("#usr-message").get(0).style = "color: green";
      $("#usr-message").text(
        "Thank you for your message, we will get back to you as soon as possible!"
      );
    });
  } else {
    $("#usr-message").text("Please fill all required fields");
    $("#usr-message").get(0).style = "color: red";
  }
}

