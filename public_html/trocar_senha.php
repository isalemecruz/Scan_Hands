<?php
session_start();

$host = "localhost";
$usuario = "u357936358_librinha";
$senha = "Librinh4tcc#";
$banco = "u357936358_scanhands";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die(json_encode(["status" => "erro", "mensagem" => "Erro ao conectar com o banco de dados."]));
}

$email = $_SESSION['recupera_email'] ?? '';
$novaSenha = $_POST['senha'] ?? '';

if (!$email || !$novaSenha) {
    echo json_encode(["status" => "erro", "mensagem" => "Dados inválidos."]);
    exit;
}

// Atualiza a senha
$stmt = $conn->prepare("UPDATE usuario SET senha = ? WHERE email = ?");
$stmt->bind_param("ss", $novaSenha, $email);

if ($stmt->execute()) {
    echo json_encode(["status" => "ok", "mensagem" => "Senha atualizada com sucesso."]);
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Erro ao atualizar a senha."]);
}

$conn->close();
?>
