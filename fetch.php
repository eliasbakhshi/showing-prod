<?php

$response = [];
$response["status"] = 200;

include_once 'inc/db.php';
include_once 'inc/functions.php';
global $DB;

LoadString("action");

switch ($action) {
  case 'updateArticles':
    LoadString('categories');
    LoadInt('minTemperature');
    LoadInt('maxTemperature');
    LoadInt('maxPrice');

    $theQuery = sprintf("SELECT DISTINCT articles.id,
                      articles.id as id,
                      articles.name,
                      articles.description,
                      articles.image_id,
                      articles.category_id,
                      categories.id as cat_id,
                      attributes.max_value,
                      attributes.min_value,
                      prices.price
                  FROM articles
                  INNER JOIN categories ON articles.category_id = categories.id
                  INNER JOIN attributes ON  articles.id = attributes.article_id
                  INNER JOIN prices ON articles.id = prices.article_id 
                  WHERE articles.category_id IN(%s) and attributes.min_value >= %s and attributes.max_value <= %s and attributes.name = 'Temperature' and prices.price BETWEEN 0 AND %s;", $categories, $minTemperature, $maxTemperature, $maxPrice);


    $getInfo = $DB->prepare($theQuery);
    $getInfo->execute();

    if ($getInfo->rowCount()) {
      $driversInfo = $getInfo->fetchAll();
      $response["articles"] = $driversInfo;
    }
    break;

  default:
    $response['status'] = 404;
    break;
}
header('Content-Type: application/json');
print(json_encode($response));