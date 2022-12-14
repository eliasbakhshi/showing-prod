let categories = document.querySelectorAll("input[type='checkbox']"),
  productList = document.querySelector(".product-list"),
  minTemperatures = document.querySelectorAll(".minTemperature"),
  maxTemperatures = document.querySelectorAll(".maxTemperature"),
  showingMinTemperatures = document.querySelector(".showingMinTemperatures"),
  showingMaxTemperatures = document.querySelector(".showingMaxTemperatures"),
  minTemperature = 0,
  maxTemperature = 0,
  selectedCategories = [];


  // Get all categories on load
  categories.forEach((category) => {
    if (category.id !== "allCat") selectedCategories.push(category.id);
  });

const getFilters = () => {
  minTemperature = showingMinTemperatures.innerHTML.trim();
  maxTemperature = showingMaxTemperatures.innerHTML.trim();
  maxPrice = document.getElementById("maxPrice").innerHTML.trim();
};

const updateArticles = async () => {
  if (selectedCategories.length) {
    getFilters();
    let url = "?dir=fetch&action=updateArticles&categories=" + selectedCategories + "&minTemperature=" + minTemperature + "&maxTemperature=" + maxTemperature + "&maxPrice=" + maxPrice;
    let response = await fetch(url);
    if (response.status === 200) {
      let data = await response.json();
      productList.innerHTML = "";
      if (data.articles) {
        data.articles.map(article => {
          productList.innerHTML += `<div class="card product id_${article.id}">
                                      <img src="/images/${article.image_id}.jpg" class="card-img-top" alt="...">
                                      <div class="card-body">
                                        <h5 class="card-title">${article.name}</h5>
                                        <p class="card- ">${article.description}</p>
                                        <a href="#" class="btn btn-primary">Go somewhere</a>
                                      </div>
                                    </div>`;
        })
      } else {
        productList.innerHTML = "<div>There is no article to show.</div>";
      }
    } else {
      console.log(
        "Something went wrong with the error code " + response.status
      );
    }
  } else {
    productList.innerHTML = "<div>There is no article to show.</div>";
  }
};

updateArticles();

// Update the min temperature
minTemperatures.forEach(e => {
  e.addEventListener("click", event => {
    showingMinTemperatures.innerHTML = event.target.innerHTML;
    updateArticles();
  })
})
// Update the max temperature
maxTemperatures.forEach(e => {
  e.addEventListener("click", event => {
    showingMaxTemperatures.innerHTML = event.target.innerHTML;
    updateArticles();
  })
})
// When categories changes
if (categories) {
  categories.forEach((category) => {
    category.addEventListener("click", () => {
      // toggle "All Categories if any category is selected"
      if (category.id === "allCat") {
        categories.forEach((category) => {
          if (category.id !== "allCat") category.checked = false;
        });
      } else {
        document.getElementById("allCat").checked = false;
      }

      // Update the selected categories
        selectedCategories = [];
        if (category.id === "allCat" && category.checked) {
          categories.forEach((category) => {
            if (category.id !== "allCat") selectedCategories.push(category.id);
          });
        } else {
          categories.forEach((category) => {
            if (category.checked && category.id !== "allCat") {
              selectedCategories.push(category.id);
            }
          });
        }
      updateArticles();
    });
  });
}

// For the price range slider
let sliderOne = document.getElementById("sliderOne");
let sliderTwo = document.getElementById("sliderTwo");
let displayValOne = document.getElementById("minPrice");
let displayValTwo = document.getElementById("maxPrice");
let minGap = 0;
let sliderTrack = document.querySelector(".slider-track");
let sliderMaxValue = sliderOne.max;
if (sliderOne && sliderTwo) {
  sliderOne.addEventListener("mousedown", (e) => {
    e.target.addEventListener("mousemove", slideOne);
  });

  sliderOne.addEventListener("mouseup", (e) => {
    e.target.removeEventListener("mousemove", slideOne);
    updateArticles();
  });
  sliderTwo.addEventListener("mousedown", (e) => {
    e.target.addEventListener("mousemove", slideTwo);
  });
  sliderTwo.addEventListener("mouseup", (e) => {
    e.target.removeEventListener("mousemove", slideTwo);
    updateArticles();
  });

  function slideOne() {
    if (parseInt(sliderTwo.value) - parseInt(sliderOne.value) <= minGap) {
      sliderOne.value = parseInt(sliderTwo.value) - minGap;
    }
    displayValOne.textContent = sliderOne.value;
    fillColor();
  }
  function slideTwo() {
    if (parseInt(sliderTwo.value) - parseInt(sliderOne.value) <= minGap) {
      sliderTwo.value = parseInt(sliderOne.value) + minGap;
    }
    displayValTwo.textContent = sliderTwo.value;
    fillColor();
  }
  function fillColor() {
    percent1 = (sliderOne.value / sliderMaxValue) * 100;
    percent2 = (sliderTwo.value / sliderMaxValue) * 100;
    sliderTrack.style.background = `linear-gradient(to right, #dadae5 ${percent1}% , #0275ff ${percent1}% , #0275ff ${percent2}%, #dadae5 ${percent2}%)`;
  }

  window.onload = function () {
    slideOne();
    slideTwo();
  };
}
