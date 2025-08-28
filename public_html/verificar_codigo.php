<?php
session_start();

$host = "localhost";
$usuario = "u357936358_librinha";
$senha = "Librinh4tcc#";
$banco = "u357936358_scanhands";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die(json_encode(["status" => "erro", "mensagem" => "Erro ao conectar com banco."]));
}

$email = $_SESSION['recupera_email'] ?? '';
$codigoDigitado = $_POST['codigo'] ?? '';

if (!$email) {
    echo json_encode(["status" => "erro", "mensagem" => "Sessão expirada."]);
    exit;
}

// Verifica se o código bate com o do banco
$stmt = $conn->prepare("SELECT * FROM codigos WHERE email = ? AND codigo = ?");
$stmt->bind_param("ss", $email, $codigoDigitado);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Remove código usado
    $conn->query("DELETE FROM codigos WHERE email = '$email'");
    echo json_encode(["status" => "ok"]);
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Código inválido."]);
}
?>
