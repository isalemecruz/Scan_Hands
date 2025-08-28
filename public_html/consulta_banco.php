<?php
$servername = "localhost";
$username = "seu_usuario";
$password = "sua_senha";
$dbname = "seu_banco";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Pegar parâmetro da URL com segurança
$nome = $_GET['nome'] ?? '';

// Usar Prepared Statement para evitar SQL Injection
$stmt = $conn->prepare("SELECT qtd_aluno FROM turma WHERE nome = ?");
$stmt->bind_param("s", $nome);
$stmt->execute();

$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(["erro" => "Turma não encontrada"]);
}

$stmt->close();
$conn->close();
?>
