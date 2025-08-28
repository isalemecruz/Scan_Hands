<?php
session_start();

$host = "localhost";
$usuario = "u357936358_librinha";
$senha = "Librinh4tcc#";
$banco = "u357936358_scanhands";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$email = $conn->real_escape_string($_POST['email'] ?? '');
$senhaDigitada = $conn->real_escape_string($_POST['senha'] ?? '');
$tipoSelecionado = $conn->real_escape_string($_POST['tipo'] ?? '');

$sql = "SELECT * FROM usuario WHERE email = '$email' LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    
    if ($usuario['tipo'] === $tipoSelecionado && $usuario['senha'] === $senhaDigitada) {
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['tipo'] = $usuario['tipo'];
        $_SESSION['email'] = $usuario['email'];

        // Redirecionamento com base no tipo
        if ($usuario['tipo'] === 'aluno') {
            header("Location: ./TCC/assets/paginas/inicioAluno.php");
        } elseif ($usuario['tipo'] === 'secretaria') {
            header("Location: ./TCC/assets/paginas/inicioSecretaria.php");
        } elseif ($usuario['tipo'] === 'professor') {
            header("Location: ./TCC/assets/paginas/inicioProfessor.php");
        } else {
            $_SESSION['erro_login'] = "Tipo de usuário inválido.";
            header("Location: index.php");
        }

        exit();
    } else {
        $_SESSION['erro_login'] = "Senha ou tipo de usuário incorretos.";
        header("Location: index.php");
        exit();
    }
} else {
    $_SESSION['erro_login'] = "Usuário não encontrado.";
    header("Location: index.php");
    exit();
}

$conn->close();
