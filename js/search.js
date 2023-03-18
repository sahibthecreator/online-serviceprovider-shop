document.getElementById("searchright").addEventListener("focusout", () => {
  setTimeout(() => {
    $(".results").get(0).innerHTML = "";
  }, 100);
});
document.getElementById("searchleft").addEventListener("focusout", () => {
  setTimeout(() => {
    $(".results").get(1).innerHTML = "";
  }, 100);
});

$(document).ready(function () {
  $("#searchright").on("keyup input", function () {
    /* Get input value on change */
    var inputVal = $(this).val();
    var resultDropdown = $(this).parent().siblings(".results");
    console.log(resultDropdown);
    if (inputVal.length) {
      $.get("api/getSearchResults.php?v=" + Date.now(), {
        term: inputVal,
      }).done(function (data) {
        // Display the returned data in browser
        resultDropdown.html(data);
      });
    } else {
      resultDropdown.empty();
    }
  });
  $("#searchleft").on("keyup input", function () {
    /* Get input value on change */
    var inputVal = $(this).val();
    var resultDropdown = $(this).siblings(".results");
    console.log(resultDropdown);
    if (inputVal.length) {
      $.get("api/getSearchResults.php?v=" + Date.now(), {
        term: inputVal,
      }).done(function (data) {
        // Display the returned data in browser
        resultDropdown.html(data);
      });
    } else {
      resultDropdown.empty();
    }
  });
});

//Currency changer
function changeCurrency(value) {
  $("select[name='currency']").val(value);
  data = {
    currency: value,
  };
  fetch("api/changeCurrency.php?v=" + Date.now(), {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  }).then(() => {
    location.reload();
  });
}
