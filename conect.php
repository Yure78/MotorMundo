<?php
$mysqli = new mysqli(
  'localhost',
  'admin',
  'QueSenhaForteVoceMeSugereSeuIA?123!',
  'motorSimulacaoMundo'
);
$result = $mysqli->query("SELECT 1 AS test");

var_dump($result->fetch_assoc());


$result = $mysqli->query("SHOW TABLES");

while ($row = $result->fetch_row()) {
    print_r($row);
}

?>