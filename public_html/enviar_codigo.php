<?php
session_start();

// Conexão com o banco
$host = "localhost";
$usuario = "u357936358_librinha";
$senha = "Librinh4tcc#";
$banco = "u357936358_scanhands";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die(json_encode(["status" => "erro", "mensagem" => "Falha na conexão com o banco."]));
}

$email = $_POST['email'] ?? '';
$email = trim($email);

// Verifica se e-mail existe
$stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "erro", "mensagem" => "E-mail não cadastrado."]);
    exit;
}

// Gera código
$codigo = str_pad(rand(0, 99999999), 8, "0", STR_PAD_LEFT);

// Salva no banco (apaga código antigo para o mesmo e-mail)
$conn->query("DELETE FROM codigos WHERE email = '$email'");

$stmtInsert = $conn->prepare("INSERT INTO codigos (codigo, email) VALUES (?, ?)");
$stmtInsert->bind_param("ss", $codigo, $email);
$stmtInsert->execute();

// Envia o e-mail (simulação ou real com mail())
$assunto = "Código de verificação - Scan Hands";
$mensagem = "Seu código de verificação é: $codigo";

$headers = "From: no-reply@scanhands.com.br\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$enviado = mail($email, $assunto, $mensagem, $headers);

if ($enviado) {
    $_SESSION['recupera_email'] = $email;
    echo json_encode(["status" => "ok", "mensagem" => "Código enviado com sucesso."]);
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Falha ao enviar o e-mail."]);
}

$conn->close();
?>
