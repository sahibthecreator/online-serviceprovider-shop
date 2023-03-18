<nav>
    <div class="menuIcon" onclick="MenuBtn()">
        <div class="bar1"></div>
        <div class="bar2"></div>
        <div class="bar3"></div>
    </div>
    <img src="../img/logo.webp" />
    <div class="links">
        <a href="index.php">Main</a>
        <a href="view-packages.php">Packages</a>
        <a href="visits.php">Visits</a>
        <a href="orders.php">Orders</a>
    </div>
</nav>
<script>
    $(function() {
        // this will get the full URL at the address bar
        var url = window.location.href;
        // passes on every "a" tag
        var page = url.substring(url.lastIndexOf("/") + 1, url.length);
        if (page == "" || page == "index.php") {
            $("nav a").get(0).className += "selected";
        } else if (page == "view-packages.php") {
            $("nav a").get(1).className += "selected";
        } else if (page == "visits.php") {
            $("nav a").get(2).className += "selected";
        } else if (page == "orders.php") {
            $("nav a").get(3).className += "selected";
        }

    });

    function MenuBtn() {
        var x = document.getElementsByClassName("links").item(0);
        document.getElementsByClassName("menuIcon").item(0).classList.toggle("change");
        if (x.style.display === "flex") {
            x.style.display = "none";
        } else {
            x.style.display = "flex";
        }
    }
</script>