<?php
// api/tasks.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

// Incluir a conexão com o banco de dados
require_once '../config/db.php';

// Rota: GET /tasks
function getTasks() {
    global $pdo;
    $query = "SELECT * FROM tasks";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($tasks);
}

// Rota: GET /tasks/{id}
function getTaskById($id) {
    global $pdo;
    $query = "SELECT * FROM tasks WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($task);
}

// Rota: POST /tasks
function createTask() {
    global $pdo;
    $data = json_decode(file_get_contents("php://input"));
    $query = "INSERT INTO tasks (title, description) VALUES (:title, :description)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':title', $data->title);
    $stmt->bindParam(':description', $data->description);
    if ($stmt->execute()) {
        echo json_encode(["message" => "Tarefa criada com sucesso"]);
    } else {
        echo json_encode(["message" => "Erro ao criar tarefa"]);
    }
}

// Rota: PUT /tasks/{id}
function updateTask($id) {
    global $pdo;
    $data = json_decode(file_get_contents("php://input"));
    $query = "UPDATE tasks SET title = :title, description = :description, status = :status WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $data->title);
    $stmt->bindParam(':description', $data->description);
    $stmt->bindParam(':status', $data->status);
    if ($stmt->execute()) {
        echo json_encode(["message" => "Tarefa atualizada com sucesso"]);
    } else {
        echo json_encode(["message" => "Erro ao atualizar tarefa"]);
    }
}

// Rota: DELETE /tasks/{id}
function deleteTask($id) {
    global $pdo;
    $query = "DELETE FROM tasks WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        echo json_encode(["message" => "Tarefa excluída com sucesso"]);
    } else {
        echo json_encode(["message" => "Erro ao excluir tarefa"]);
    }
}

// Verifica o método HTTP e chama a função apropriada
$requestMethod = $_SERVER["REQUEST_METHOD"];
$requestUri = explode('/', trim($_SERVER['PATH_INFO'], '/'));

if ($requestUri[0] == 'tasks') {
    if ($requestMethod == 'GET') {
        if (isset($requestUri[1])) {
            getTaskById($requestUri[1]);
        } else {
            getTasks();
        }
    } elseif ($requestMethod == 'POST') {
        createTask();
    } elseif ($requestMethod == 'PUT' && isset($requestUri[1])) {
        updateTask($requestUri[1]);
    } elseif ($requestMethod == 'DELETE' && isset($requestUri[1])) {
        deleteTask($requestUri[1]);
    } else {
        echo json_encode(["message" => "Método não suportado"]);
    }
}
?>