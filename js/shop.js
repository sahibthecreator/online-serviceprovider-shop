let packages;
(async () => {
  packages = await getAllPackages();
  $("#loading").hide();
  displayContent();
})();

async function getAllPackages() {
  const response = await fetch("api/getPackages.php?v=" + Date.now()); // Wait for the response
  const packages = await response.json(); // Parse the JSON response
  return packages;
}

function displayContent() {
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  const socialMedia = urlParams.get('socMedia');
  const type = urlParams.get('type');
  if (type) {
    createBreadcrumbs(socialMedia, type);
    displayPackages(socialMedia, type);
  }
  else if (socialMedia) {
    createBreadcrumbs(socialMedia);
    if (socialMedia == "browse-all")
      displayAllPackages()
    else
      displaySocialMediaBoostTypes(socialMedia);
  }
  else {
    displayAllSocialMedias();
  }
}

function displayPackages(socialMedia, type) {
  var recommendationList = document.createElement("div");
  recommendationList.classList.add("product-list");
  recommendationList.id = "recommendation-list";
  let recommendationTypes = [];

  for (var i = 0; i < packages.length; i++) {
    let package = packages[i];
    if (package.Name == socialMedia && package.Type == type) {
      let product = createProduct(package.Id, package.Name, package.Pack, package.Amount, package.Type, package.Price);
      $("#product-list").append(product);
    }
    if (package.Name == socialMedia && !recommendationTypes.includes(package.Type) && package.Type != type) {
      recommendationTypes.push(package.Type);
      let recommendedBoostType = createBoostType(socialMedia, package.Type)
      recommendationList.append(recommendedBoostType);
    }
  }
  // recommendations
  var recomendText = document.createElement("h1");
  recomendText.classList.add("recommendation-text");
  recomendText.textContent = "Other boosts";

  $(".container").append(recomendText);
  $(".container").append(recommendationList);
}

function displaySocialMediaBoostTypes(socialMedia) {
  let types = [];
  for (var i = 0; i < packages.length; i++) {
    let package = packages[i];
    if (package.Name == socialMedia && !types.includes(package.Type)) {
      types.push(package.Type);
      let boostType = createBoostType(socialMedia, package.Type)
      $("#product-list").append(boostType);
    }
  }
}

function displayAllSocialMedias() {
  let socialMedias = [];
  for (var i = 0; i < packages.length; i++) {
    let package = packages[i];
    if (!socialMedias.includes(package.Name)) {
      socialMedias.push(package.Name);
      let socialMediaPack = createSocialMediaPack(package.Name)
      $("#product-list").append(socialMediaPack);
    }
  }
  let browseAllPack = createSocialMediaPack("browse-all")
  $("#product-list").append(browseAllPack);
}

function displayAllPackages() {
  document.getElementById("product-list").innerHTML = "";
  for (var i = 0; i < packages.length; i++) {
    var package = packages[i];
    let product = createProduct(
      package.Id,
      package.Name,
      package.Pack,
      package.Amount,
      package.Type,
      package.Price
    );
    document.getElementById("product-list").appendChild(product);
  }
}

function createSocialMediaPack(socialmedia) {
  let header = socialmedia.replace(/-/g, ' ');
  header = capitalizeWords(header);
  header = socialmedia == "browse-all" ? "Browse All" : header;
  var pack = document.createElement("a");
  pack.setAttribute('href', '?socMedia=' + socialmedia);
  pack.classList.add("product");
  pack.innerHTML =
    '<div class="product-container ' +
    socialmedia + '"> <div class="row1">' + "<p>" + header +
    '</p> </div> <div class="row2">' +
    '<img src="img/' + socialmedia +
    '-white-icon.webp" /></div></div>';
  return pack;
}

function createBoostType(socialMedia, type) {
  let imageName = type.replace(/ /g, '-');
  imageName = imageName.toLowerCase();
  imageName = imageName == "page-likes" ? "page-like" : imageName;
  if (socialMedia == "facebook" || socialMedia == "linkedin" || socialMedia == "telegram" || socialMedia == "youtube") {
    if (type == "Likes")
      imageName = "thumbs-up";
    if (type == "Channel Members" || type == "Subscribers")
      imageName = "add-user";
  }
  var pack = document.createElement("a");
  pack.setAttribute('href', '?socMedia=' + socialMedia + '&type=' + type);
  pack.classList.add("product");
  pack.innerHTML =
    '<div class="product-container ' +
    socialMedia + '"> <div class="row1">' + "<p>" + type +
    '</p> </div> <div class="row2">' +
    '<img src="img/' + imageName +
    '-white-icon.webp" /></div></div>';
  return pack;
}


function createProduct(id, name, pack, amount, type, price) {
  let data = convertPrices(price);
  var packageElement = document.createElement("a");
  packageElement.setAttribute("href", "product.php?id=" + id + "");
  packageElement.classList.add("product");
  packageElement.innerHTML =
    '<div class="product-container ' +
    name +
    '"> <div class="row1">' +
    '<img src="img/' +
    name +
    '-white-icon.webp" />' +
    "<p>" +
    pack +
    '</p> </div> <div class="row2">';
  if (type == "Activity") {
    packageElement.getElementsByClassName("row2")[0].innerHTML +=
      '<img src="img/' +
      type.toLowerCase() +
      '-icon.webp" alt="activity icon">' +
      "<p>" +
      type +
      "</p>";
  } else {
    packageElement.getElementsByClassName("row2")[0].innerHTML +=
      '<p class="amount">' + amount + "</p>" + "<p>" + type + "</p>";
  }
  packageElement.innerHTML +=
    '<p class="price-text">' + data.symbol + " " + data.newPrice + "</p>";

  return packageElement;
}

function createBreadcrumbs(...params) {
  let socMedia = params[0].replace(/-/g, ' ');
  socMedia = capitalizeWords(socMedia);
  socMedia = params[0] == "browse-all" ? "Browse All" : socMedia;

  $("#breadcrumbs").get(0).innerHTML = '<a href="?">Shop</a>  / ' + socMedia + '';
  if (params.length > 1) {
    let serviceName = params[1]
    $("#breadcrumbs").get(0).innerHTML = '<a href="?">Shop</a>  / <a href="?socMedia=' + params[0] + '" >' + socMedia + '</a> / ' + serviceName + '';
  }
}

document
  .getElementsByClassName("view-filter")
  .item(0)
  .addEventListener("click", () => {
    setDifferentView();
  });

function setDifferentView() {
  var x = document.getElementsByClassName("view-filter").item(0);
  x.classList.toggle("view2");
  document
    .getElementsByClassName("product-list")
    .item(0)
    .classList.toggle("view-two");
}

function convertPrices(price) {
  var selected = $("select[name='currency']").val();
  let newPrice = 0;
  let symbol;
  if (selected == "GBP") {
    newPrice = price;
    symbol = "	&#163;";
  } else if (selected == "EUR") {
    newPrice = price * 1.14;
    newPrice = newPrice.toFixed(2);
    symbol = "&#8364;";
  } else if (selected == "USD") {
    newPrice = price * 1.24;
    newPrice = newPrice.toFixed(2);
    symbol = "&#36;";
  }
  return (data = {
    newPrice: newPrice,
    symbol: symbol,
  });
}

function capitalizeWords(str) {
  var splitStr = str.toLowerCase().split(' ');
  for (var i = 0; i < splitStr.length; i++) {
    splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
  }
  return splitStr.join(' ');
}