<?php
if (ALLOWED) {
  $mysql = new mysqli("localhost", "test", "test", "test");

  if ($mysql->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysql->connect_error;
    exit();
  }

  $res = [];

  // Get all data in the beginning
  $query = "SELECT MIN(min_value) as min_temperature, MAX(max_value) as max_temperature FROM attributes;";
  $query .= "SELECT id as cat_id, name as cat_name, description as cat_desc FROM categories;";
  $query .= "SELECT MIN(price) as min_price, MAX(price) as max_price FROM prices;";


  if (mysqli_multi_query($mysql, $query)) {
    do {
      $index = 0;
      /* store first result set */
      if ($result = mysqli_store_result($mysql)) {
        while ($row = mysqli_fetch_array($result)) {
          foreach ($row as $key => $value) {
            if (gettype($key) !== "integer") {
              if ($result->num_rows - 1 === 0) {
                $res[$key] = $value;
              } else {
                $res["categories"][$index][$key] = $value;
              }
            }
          }
          $index++;
        }
        mysqli_free_result($result);
      }
    } while (mysqli_next_result($mysql));
  }

  $temperatureInterval = range($res['min_temperature'], roundUp($res['max_temperature']), 10);

?>

<main class="products">
  <aside>
    <div class=categories>
      <p>Categories</p>
      <div class="category">
        <input type="checkbox" class="the-category" checked id="allCat">
        <label for="allCat">All Categories</label>
      </div>
      <?php
  foreach ($res['categories'] as $category) {
    printf('<div class="category">
    <input type="checkbox" class="the-category" id="%s">
    <label for="%s">%s</label>
  </div>', $category['cat_id'], $category['cat_id'], $category['cat_name']);
  } ?>
    </div>
    <div class="temperature">
      <p>Temperature</p>
      <div class="btn-group">
        <button type="button" class="btn btn-primary dropdown-toggle showingMinTemperatures" data-bs-toggle="dropdown"
          aria-expanded="false">
          <?php echo $temperatureInterval[0]; ?>
        </button>
        <ul class="dropdown-menu">
          <?php
  foreach ($temperatureInterval as $value) {
    printf('<li><a class="dropdown-item minTemperature">%s</a></li>', $value);
  } ?>
        </ul>
      </div>
      <span> - </span>
      <div class="btn-group">
        <button type="button" class="btn btn-primary dropdown-toggle showingMaxTemperatures" data-bs-toggle="dropdown"
          aria-expanded="false">
          <?php echo end($temperatureInterval); ?>
        </button>
        <ul class="dropdown-menu">
          <?php
  foreach ($temperatureInterval as $temperature) {
    printf('<li><a class="dropdown-item maxTemperature">%s</a></li>', $temperature);
  } ?>
        </ul>
      </div>
    </div>
    <div class="range-slider">
      <p>Price</p>
      <div class="values">
        <span id="minPrice">
          0
        </span>
        <span> - </span>
        <span id="maxPrice">
          <?php echo $res['max_price']; ?>
        </span>
      </div>
      <div class="container">
        <div class="slider-track"></div>
        <input type="range" min="0" max="<?php echo $res['max_price']; ?>" value="0" id="sliderOne">
        <input type="range" min="0" max="<?php echo $res['max_price']; ?>" value="<?php echo $res['max_price']; ?>"
          id="sliderTwo">
      </div>
    </div>
  </aside>
  <section class="product-list">
  </section>
</main>

<?php
} else {
  echo "Permission denied.";
}