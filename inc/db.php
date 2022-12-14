<?php
if (ALLOWED) {
  $dbHost = "localhost";
  $dbName = "test";
  $dbUser = "test";
  $dbPass = "test";
  $dbPort = "3306";
  $charset = "utf8mb4";
  $dbOptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ];
  $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=$charset;port=$dbPort";
  try {
    $DB = new PDO($dsn, $dbUser, $dbPass, $dbOptions);
  } catch (PDOException $err) {
    throw new PDOException(($err->getMessage()), (int)$err->getCode());
  }


} else {
  echo "Permission denied.";
}