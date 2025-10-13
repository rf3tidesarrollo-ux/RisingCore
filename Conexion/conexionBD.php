<?php

try {
    $connMS = new PDO("mysql:host=127.0.0.1;dbname=checador", "root", "");
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

function getOneString( $query ) {
  try {
    $stmt = $GLOBALS['connMS']->query( $query );
    $text = $stmt->fetch(PDO::FETCH_BOTH);
	  return $text[0];
  } catch ( Exception $pe ) {}

  return "Hola";
}

function getOneDouble( $query ) {
  try {
    $stmt = $GLOBALS['connMS']->query( $query );
    return $stmt->fetch(PDO::FETCH_ASSOC);
  } catch ( Exception $pe ) {}

  return "Hola";
}

function getOneInt( $query ) {
  try {
    $stmt = $GLOBALS['connMS']->query( $query );
    return $stmt->fetch(PDO::FETCH_ASSOC);
  } catch ( Exception $pe ) {}

  return "Hola";
}
?>