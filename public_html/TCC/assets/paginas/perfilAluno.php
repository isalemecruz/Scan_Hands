<?php
session_start();

// Redireciona se o usuário não estiver logado
if (!isset($_SESSION['email'])) {
    header("Location: ../../../index.php");
    exit();
}

// Conecta ao banco
$host = "localhost";
$usuario = "u357936358_librinha";
$senha = "Librinh4tcc#";
$banco = "u357936358_scanhands";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro de conexão com o banco: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$nome = $telefone = $turma = $senhaUsuario = '';

// Busca dados do usuário
$stmt = $conn->prepare("SELECT u.nome, u.telefone, u.senha, a.turma FROM usuario u
LEFT JOIN aluno a ON u.id_usuario = a.id_usuario
WHERE u.email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $dados = $result->fetch_assoc();
    $nome = $dados['nome'];
    $telefone = $dados['telefone'];
    $senhaUsuario = $dados['senha'];
    $turma = $dados['classificacao'] ?? 'Não informado';
} else {
    $nome = "Desconhecido";
    $turma = "Não encontrado";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Perfil do Aluno</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" href="../img/librinhaSemFundo.png" type="image/png">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #eaeaea;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: white;
            max-width: 500px;
            /* largura aumentada */
            width: 100%;
            padding: 60px 30px 50px;
            border-radius: 30px;
            min-height: 650px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            position: relative;
        }



        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 20px;
            color: black;
            cursor: pointer;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
            color: #69BCD0;
            font-size: 24px;
            font-weight: 600;
        }

        .profile-wrapper {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            color: #555;
            margin: 0 auto 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }

        .change-photo-btn {
            font-size: 12px;
            color: #69BCD0;
            background: none;
            border: none;
            cursor: pointer;
            text-decoration: underline;
            display: inline-block;
        }

        .email-display {
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: #444;
            margin-bottom: 4px;
        }

        .form-group input {
            width: 100%;
            padding: 10px 35px 10px 10px;
            border: none;
            border-bottom: 1.5px solid #000;
            font-size: 14px;
            background-color: transparent;
            outline: none;
        }

        .form-group i {
            position: absolute;
            right: 10px;
            top: 30px;
            transform: translateY(-50%);
            color: #555;
        }

        .alterar-btn {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            background-color: #69BCD0;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .alterar-btn:hover {
            background-color: #4fa4b8;
        }

        input[type="file"] {
            display: none;
        }
    </style>
</head>

<body>

   <div class="container">
    <div class="back-button" onclick="window.history.back()">
        <i class="fas fa-arrow-left"></i>
    </div>

    <h2 class="title">Dados Pessoais</h2>

    <div class="profile-wrapper">
        <div class="profile-pic" id="profileImage">
            <i class="fas fa-user"></i>
        </div>
    </div>

    <p class="email-display" id="emailExibido"><?= htmlspecialchars($email) ?></p>

    <div class="form-group">
        <label>Nome</label>
        <input disabled type="text" value="<?= htmlspecialchars($nome) ?>">
        <i class="fas fa-user"></i>
    </div>

    <div class="form-group">
        <label>Turma</label>
        <input disabled type="text" value="<?= htmlspecialchars($turma) ?>">
    </div>

    <div class="form-group">
        <label>Telefone</label>
        <input disabled type="text" value="<?= htmlspecialchars($telefone) ?>">
        <i class="fas fa-mobile-alt"></i>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input disabled type="email" value="<?= htmlspecialchars($email) ?>">
        <i class="fas fa-envelope"></i>
    </div>

    <div class="form-group">
        <label>Senha</label>
        <input disabled type="password" id="senhaInput" value="<?= htmlspecialchars($senhaUsuario) ?>">
        <i class="fas fa-lock" id="toggleSenha" style="cursor: pointer;"></i>
    </div>
</div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('profileImage');
                output.innerHTML = `<img src="${reader.result}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">`;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
        
    </script>

    
    <!-- <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('profileImage');
                output.innerHTML = `<img src="${reader.result}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">`;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function alternarSenha() {
            const input = document.getElementById("senhaInput");
            const icon = document.getElementById("toggleSenha");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script> -->

</body>

</html>