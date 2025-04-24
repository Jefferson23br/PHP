<?php
// config/db.php

$host = 'localhost';
$dbname = 'tasks_db';
$username = 'root'; // Use seu nome de usuário do banco de dados
$password = ''; // Use sua senha do banco de dados

try {
    // Criar a conexão com o banco de dados usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>