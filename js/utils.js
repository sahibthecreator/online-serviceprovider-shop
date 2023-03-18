const products = document.querySelector(".slider");
const nxtBtn = document.querySelector(".right-arrow");
const preBtn = document.querySelector(".left-arrow");

if (products != null || nxtBtn != null) {
  let containerDimensions = products.getBoundingClientRect();
  let containerWidth = containerDimensions.width - 22;
  nxtBtn.addEventListener("click", () => {
    products.scrollLeft += containerWidth;
  });
  preBtn.addEventListener("click", () => {
    products.scrollLeft -= containerWidth;
  });
}

// Animation handler
const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add("show");
    }
  });
});
const hidenElements = document.querySelectorAll(".hidden");
hidenElements.forEach((el) => observer.observe(el));

// Save Visits
saveVisit();
function saveVisit() {
  var path = window.location.href;
  var page = path.split("/").pop();
  let deviceType;
  if (
    /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
      navigator.userAgent
    )
  )
    deviceType = "Mobile";
  else deviceType = "Desktop";
  page = page == "" || page == "index.php" ? "Home" : page;

  const data = {
    Page: page,
    Device: deviceType,
  };
  const response = fetch("api/saveLog.php?v=" + Date.now(), {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
}

// CTA navigation added
$('div[id^="ctaShopPage"]').each(function (i, button) {
  $(this).click(() => {
    location.href = "shop.php";
  });
});

$("#subscribeUserBtn").click(() => {
  let email = $("#subscriberEmail").val();
  if (email.length > 0) {
    const data = {
      Email: email,
    };
    const response = fetch("api/saveSubscriber.php?v=" + Date.now(), {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    }).then((r) => r.json());
    $("#subscribeUserBtn").prop("disabled", true);
    $("#subscribeUserBtn p").text("Success");
  } else {
    $(".col4 .error-message").text("Enter your e-mail");
  }
});

(function ($) {
  $.fn.numberstyle = function (options) {
    var settings = $.extend(
      {
        value: 0,
        step: undefined,
        min: undefined,
        max: undefined,
      },
      options
    );

    return this.each(function (i) {
      var input = $(this);

      var container = document.createElement("div"),
        btnAdd = document.createElement("div"),
        btnRem = document.createElement("div"),
        min = settings.min ? settings.min : input.attr("min"),
        max = settings.max ? settings.max : input.attr("max"),
        value = settings.value ? settings.value : parseFloat(input.val());
      container.className = "numberstyle-qty";
      btnAdd.className =
        max && value >= max ? "qty-btn qty-add disabled" : "qty-btn qty-add";
      btnAdd.innerHTML = "+";
      btnRem.className =
        min && value <= min ? "qty-btn qty-rem disabled" : "qty-btn qty-rem";
      btnRem.innerHTML = "-";
      input.wrap(container);
      input.closest(".numberstyle-qty").prepend(btnRem).append(btnAdd);

      $(document)
        .off("click", ".qty-btn")
        .on("click", ".qty-btn", function (e) {
          var input = $(this).siblings("input"),
            sibBtn = $(this).siblings(".qty-btn"),
            step = settings.step
              ? parseFloat(settings.step)
              : parseFloat(input.attr("step")),
            min = settings.min
              ? settings.min
              : input.attr("min")
              ? input.attr("min")
              : undefined,
            max = settings.max
              ? settings.max
              : input.attr("max")
              ? input.attr("max")
              : undefined,
            oldValue = parseFloat(input.val()),
            newVal;

          //Add value
          if ($(this).hasClass("qty-add")) {
            (newVal = oldValue >= max ? oldValue : oldValue + step),
              (newVal = newVal > max ? max : newVal);

            if (newVal == max) {
              $(this).addClass("disabled");
            }
            sibBtn.removeClass("disabled");

            //Remove value
          } else {
            (newVal = oldValue <= min ? oldValue : oldValue - step),
              (newVal = newVal < min ? min : newVal);

            if (newVal == min) {
              $(this).addClass("disabled");
            }
            sibBtn.removeClass("disabled");
          }

          //Update value
          input.val(newVal).trigger("change");
        });

      input.on("change", function () {
        const val = parseFloat(input.val()),
          min = settings.min
            ? settings.min
            : input.attr("min")
            ? input.attr("min")
            : undefined,
          max = settings.max
            ? settings.max
            : input.attr("max")
            ? input.attr("max")
            : undefined;

        if (val > max) {
          input.val(max);
        }

        if (val < min) {
          input.val(min);
        }
      });
    });
  };

  /*
   * Init
   */

  $(".numberstyle").numberstyle();
})(jQuery);
