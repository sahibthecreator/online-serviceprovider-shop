async function getPackageById(id) {
  const response = await fetch("api/getPackages.php?id=" + id); // Wait for the response
  const packages = await response.json(); // Parse the JSON response
  return packages;
}

$(".paste-btn").click(() => {
  navigator.clipboard.readText().then((copied) => {
    $(".text-input[name='link']").val(copied);
  });
});

function translateNumber(num) {
  if (num >= 1000) {
    var numInK = (num / 1000).toFixed(0);
    return numInK + "k";
  } else return num;
}

document.querySelectorAll(".rounded-product p").forEach((el) => {
  let num = translateNumber(el.innerHTML);
  el.innerHTML = num;
});

var url = window.location.href;
var idMatch = url.match(/\?id=([^&]*)/);
document.querySelectorAll(".options a").forEach((el) => {
  var idValue = idMatch[1];
  id = el.href.match(/\?id=([^&]*)/);
  if (idValue == id[1]) {
    el.querySelector(".rounded-product").classList.add("selected");
  }
});

let socialMedia = $(".product-page.container .col1 .product-container .row1 p").text();
socialMedia = socialMedia.toLowerCase();
let service = $(".product-page.container .caption p").text().split(" ").pop();
createBreadcrumbs(socialMedia.replace(/ /g, '-'), service);

function createBreadcrumbs(...params) {
  let socMedia = params[0].replace(/-/g, ' ');
  socMedia = capitalizeWords(socMedia);
  socMedia = params[0] == "browse-all" ? "Browse All" : socMedia;

  $("#breadcrumbs").get(0).innerHTML = '<a href="shop.php">Shop</a>  / <a href="shop.php?socMedia=' + params[0] + '" >' + socMedia + '</a> / <a href="shop.php?socMedia=' + params[0] + '&type=' + params[1] + '" >' + params[1] + '</a>';
}

function capitalizeWords(str) {
  var splitStr = str.toLowerCase().split(' ');
  for (var i = 0; i < splitStr.length; i++) {
    splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
  }
  return splitStr.join(' ');
}
