<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../index.php");
    exit();
}
$emailLogado = $_SESSION['email'];

// Conexão com o banco
$servername = "localhost";
$username = "u357936358_librinha";
$password = "Librinh4tcc#";
$dbname = "u357936358_scanhands";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Buscar foto do usuário
$sql = "SELECT foto_perfil FROM usuario WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $emailLogado);
$stmt->execute();
$stmt->bind_result($foto_perfil);
$stmt->fetch();
$stmt->close();
$conn->close();

// Caso exista foto, converter para base64
$fotoPerfilBase64 = "";
if (!empty($foto_perfil)) {
    $fotoPerfilBase64 = "data:image/jpeg;base64," . base64_encode($foto_perfil);
} else {
    $fotoPerfilBase64 = "../img/iconPerfil.png"; // fallback
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atividades</title>
    <link rel="stylesheet" href="../css/padrao.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
        integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <link rel="icon" href="../img/librinhaSemFundo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/aulasAluno.css">
</head>

<body>
    <div class="sidebar" style="margin-right: 50px;">
        <div class="sidebar-top">
            <button id="toggleSidebar">
                <img id="toggleIcon" src="../img/iconVoltar.png" style="width: 20px; height: 20px;" alt="Voltar">
            </button>

            <!-- FOTO DE PERFIL DO BANCO -->
            <img src="<?php echo $fotoPerfilBase64; ?>" alt="avatar" style="width:80px; height:80px; border-radius:50%;" />

            <p class="email"><?php echo htmlspecialchars($emailLogado); ?></p>
            <button style="font-size: 12px;" onclick="window.location.href='perfilAluno.php'">Ver Perfil</button>
            <nav>
                <a href="inicioAluno.php"><img src="../img/iconHome.png" alt=""><span> Início</span></a>
                <a href="aulasAluno.php"><img src="../img/iconAula.png" alt=""><span> Aulas</span></a>
                <a href="atividadesAluno.php"><img src="../img/iconAtv.png" alt=""><span> Atividades</span></a>
                <a href="#"><img src="../img/iconChat.png" alt=""><span> Chat</span></a>
            </nav>
        </div>
        <a class="logout" href="../../index.php">
            <img style="height: 40px; width: 40px;" src="../img/iconSair.png" alt="">
            <span> Sair</span>
        </a>
    </div>

    <div class="main" style="background-color: #ededed;">

        <div class="combo" style="margin-left: 10px;">

            <p style="font-weight:500;margin-top: 100px;">Clique no botão abaixo para ver outros capítulos: </p>

            <div class="custom-select-wrapper">
                <div class="custom-select">
                    <span style="font-weight: 500;" class="selected-option">Selecionar atividade</span>
                    <div class="options">
                        <div class="option" style="font-weight: 500;">Atividade 1</div>
                        <div class="option" style="font-weight: 500;">Atividade 2</div>
                    </div>
                </div>
            </div>

        </div>

        <div class="cards-wrapper" style="margin-right: 60px;">

            <div class="cards" style="margin-top: 30px;">
                <div class="card" style="margin-right: 60px;">
                    <p>Atividades Resolvidas</p>

                    <div class="progress-text" style="margin-top: 100px;">18/270</div>
                    <div class="progress-bar"></div>
                </div>
                <div class="card" style="margin-left: 60px;">
                    <p>Aulas Assistidas</p>
                    <div class="progress-text" style="margin-top: 100px;">18/270</div>
                    <div class="progress-bar"></div>
                </div>
                <div class="card">
                    <p>Capítulos concluídos</p>
                    <div class="progress-text" style="margin-top: 100px;">2/30</div>
                    <div class="progress-bar"></div>
                </div>
                <div class="card" style="margin-left: 60px;">
                    <p>Tempo de tela</p>
                    <div class="progress-text" style="margin-top: 100px;">180h</div>
                    <div class="progress-bar"></div>
                </div>
            </div>
        </div>


    </div>

    <script>
        const customSelect = document.querySelector('.custom-select');
        const selectedOption = document.querySelector('.selected-option');
        const options = document.querySelectorAll('.option');

        customSelect.addEventListener('click', () => {
            customSelect.classList.toggle('open');
        });

        options.forEach(option => {
            option.addEventListener('click', () => {
                selectedOption.textContent = option.textContent;
                customSelect.classList.remove('open');
            });
        });

        document.addEventListener('click', (e) => {
            if (!customSelect.contains(e.target)) {
                customSelect.classList.remove('open');
            }
        });
    </script>

    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.querySelector('.sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>

</html>
