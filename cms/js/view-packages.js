// $(".price-field").dblclick(function () {
//   alert("Handler for .dblclick() called.");
// });

$(document).on("dblclick", ".price-field", function () {
  var current = $(this).text();
  var id = $(this).attr("id");
  $("#" + id).html('<input id="newcont"> </input>');
  $("#newcont").val(current);
  $("#newcont").focus();

  $("#newcont")
    .focus(function () {
      console.log("in");
    })
    .blur(function () {
      var newcont = $("#newcont").val();
      if (
        confirm("Are you sure you want to save new price into the database?")
      ) {
        $("#" + id).text(newcont);
        updatePrice(id, newcont);
      } else {
        $("#" + id).text(current);
      }
    });
});

function updatePrice(id, price) {
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);

  const data = {
    Price: price,
    Id: id,
  };
  if (urlParams.get('ru') == null) {
    const response = fetch("api/updatePrice.php?v=" + Date.now(), {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    })
      .then((response) => response.json())
      .then((response) => {
        if (response == "Failed") alert("Failed to update price");
      });
  } else {
    const response = fetch("api/updatePrice.php?ru=yes&v=" + Date.now(), {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    })
      .then((response) => response.json())
      .then((response) => {
        if (response == "Failed") alert("Failed to update price");
      });
  }

}
