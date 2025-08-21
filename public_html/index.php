<?php
session_start();
$msgErro = null;
$inputErro = false;
if (isset($_SESSION['erro_login'])) {
    $msgErro = $_SESSION['erro_login'];
    $inputErro = true;
    unset($_SESSION['erro_login']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Scan Hands - Aprendizado de Libras</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
        integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous" />
    <link rel="icon" href="../TCC/assets/img/librinhaSemFundo.png" type="image/png" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="./TCC/assets/css/login.css" />
    <style>
        body {
            background-color: #eaeaea;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            left: -6%;
            position: relative;
        }

        .left-column {
            position: relative;
        }

        .left-column img {
            width: 110%;
            height: auto;
            border-radius: 10px;
            position: absolute;
            left: 25%;
            top: 0;
            z-index: 2;
        }

        @media (max-width: 767px) {
            .left-column {
                order: -1;
                display: flex !important;
                justify-content: center;
                align-items: center;
                width: 100%;
                margin-bottom: 20px;
            }

            .left-column img {
                position: relative !important;
                left: 20px !important;
                width: 80% !important;
                max-width: 300px;
                margin: 0 auto;
                display: block;
            }
        }

        .right-column {
            background-color: #ffffff;
            padding-bottom: 26px;
            border-radius: 20px;
            position: relative;
            z-index: 1;
            left: 0.004%;
            margin-top: 136px;
        }

        .titulo {
            color: #69bcd0;
            text-shadow: 1px 4px 5px rgba(53, 53, 53, 0.39);
            margin-top: 20px;
            font-size: 50px;
            text-align: center;
            justify-content: center;
            align-items: center;
        }

        a {
            text-decoration: none;
        }

        p:hover {
            color: #69bcd0;
        }

        .custom-btn {
            padding: 20px 180px;
            border-radius: 10px;
            background-color: #69BCD0;
            border: none;
            color: white;
            font-size: 16px;
            align-items: center;
            justify-content: center;
        }

        button.btn.custom-btn:hover {
            border: none;
            color: white;
            background-color: #4a96ad;
        }

        p {
            color: black;
            font-size: 12px;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .col {
            margin-left: 210px;
            padding: 10px 15px 10px 15px;
        }

        .input-container {
            left: 5%;
            display: flex;
            align-items: center;
            width: 100%;
            max-width: 500px;
            border-bottom: 2px solid black;
            position: relative;
        }

        .input-container input {
            flex: 1;
            border: none;
            font-size: 16px;
            padding: 5px;
            outline: none;
        }

        .input-container img {
            width: 25px;
            height: 25px;
            margin-left: 10px;
        }

        .custom-select {
            background-color: #EAEAEA !important;
            width: 100%;
            max-width: max-content;
            color: #B3A5A5;
            border: 1px solid #B3A5A5;
            border-radius: 10px;
            padding: 8px 12px;
            box-shadow: 0 2px 6px #B3A5A5;
            font-weight: 500;
            appearance: none;
        }

        .custom-select:focus {
            outline: none;
            box-shadow: none;
            border: 1px solid #B3A5A5;
            background-color: #EAEAEA;
        }

        .right-column .container {
            padding-left: 0;
            padding-right: 0;
            width: 100%;
        }

        @media (max-width: 767px) {
            .right-column {
                margin-top: 10px !important;
                margin-left: 23px;
            }

            p#btnEsqueciSenha {
                margin-left: 30px;
            }

            button.btn.custom-btn {
                margin-left: -100px;
                padding: 20px !important;
            }
        }

        .error-card {
            margin-left: -280px;
            max-width: 400px;
            padding: 15px 20px;
            background-color: #f8d7da;
            border: 1px solid #f5c2c7;
            border-radius: 8px;
            color: #842029;
            text-align: center;
            font-weight: 600;
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.2);
        }

        @media (min-width: 768px) {
            .left-column .error-card {
                position: absolute;
                top: 50px;
                left: 1143px;
                width: 400px;
            }
        }

        @media (max-width: 767px) {
            .left-column .error-card {
                position: static;
                max-width: 90%;
            }

            form {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .text-center.mt-4 {
                width: 100%;
                display: flex;
                justify-content: center;
                margin-left: 0 !important;
            }

            button.btn.custom-btn {
                display: block;
                margin-left: 100px;
                width: fit-content;
            }

            .left-column {
                align-items: center;
                text-align: center;
                position: relative;
            }

            .left-column img {
                margin: 0 auto;
                display: block;
            }

            .left-column .error-card {
                position: static;
                max-width: 45%;
                margin-top: 20px;
                margin-left: 48px;
                text-align: center;
                box-shadow: 0 0 10px rgba(255, 0, 0, 0.2);
            }

            form {
                margin-left: 0 !important;
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                padding-left: 5%;
                padding-right: 5%;
            }

            button.btn.custom-btn {
                width: 45%;
                padding: 15px 0;
                font-size: 20px;
                margin-top: 10px;
                display: block;
            }

            .text-center.mt-4 {
                width: 100%;
                display: flex;
                justify-content: center;
                margin-left: 0 !important;
                margin-top: 10px;
            }
        }

        .input-error {
            border: 2px solid red !important;
        }

        .mensagem-erro-dados {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container" style="margin-top: 120px;">
        <div class="row">
            <div class="col-md-6 left-column position-relative d-flex flex-column align-items-center">
                <img src="./TCC/assets/img/logoBg.png" alt="Imagem Grande" />
                <?php if ($msgErro): ?>
                    <div class="error-card" role="alert">
                        <?= htmlspecialchars($msgErro) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-6 right-column d-flex align-items-center">
                <div class="container">
                    <form action="login.php" method="POST" id="loginForm" style="margin-left: 226px;">
                        <div class="d-flex flex-column align-items-end">
                            <div class="d-flex align-items-center mt-4">
                                <h3 class="me-3 mt-2" style="text-shadow: 0 2px 6px #B3A5A5;">Entrar como:</h3>
                                <select name="tipo" id="tipoUsuario" class="form-select custom-select <?= $inputErro ? 'input-error' : '' ?>"
                                    style="margin-left: 20px; width: 220px;">
                                    <option value="aluno">Aluno</option>
                                    <option value="professor">Professor</option>
                                    <option value="secretaria">Secretaria</option>
                                </select>
                            </div>

                            <div class="d-flex align-items-center mt-4">
                                <h3 class="me-3 mt-2"
                                    style="text-shadow: 0 2px 6px #B3A5A5; margin-right: 20px;">Email:</h3>
                                <input type="text" id="emailInputLogin" name="email" class="form-control <?= $inputErro ? 'input-error' : '' ?>"
                                    placeholder="Digite seu email"
                                    style="background-color: #EAEAEA; width: 220px; color: #B3A5A5; border-radius: 10px; padding: 8px 12px; box-shadow: 0 2px 6px #B3A5A5; font-weight: 500; outline: none;" />
                            </div>

                            <div class="d-flex align-items-center mt-4" style="margin-bottom: 30px;">
                                <h3 class="me-3 mt-2"
                                    style="text-shadow: 0 2px 6px #B3A5A5; margin-right: 20px;">Senha:</h3>
                                <input type="password" id="senhaInputLogin" name="senha" class="form-control <?= $inputErro ? 'input-error' : '' ?>"
                                    placeholder="Digite sua senha"
                                    style="background-color: #EAEAEA; width: 220px; color: #B3A5A5; border-radius: 10px; padding: 8px 12px; box-shadow: 0 2px 6px #B3A5A5; font-weight: 500; outline: none;" />
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a><p id="btnEsqueciSenha" style="text-shadow: 0 2px 6px #B3A5A5; font-size: 20px; cursor: pointer;">Esqueci minha senha</p></a>
                        </div>

                        <div id="erroCamposVazios" class="mensagem-erro-dados d-none">Insira seus dados.</div>

                        <button type="submit" class="btn custom-btn"
                            style="text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5); font-size: 25px;">
                            Entrar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Inserir Email -->
    <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-4 rounded-4 shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailModalLabel" style="color: #69BCD0; text-align: center;">Recuperar
                        Senha</h5>
                </div>
                <div class="modal-body">
                    <label for="emailInput" class="form-label">Digite seu e-mail cadastrado:</label>
                    <input type="email" class="form-control" id="emailInput" placeholder="exemplo@dominio.com">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" id="voltarEmailBtn"
                        style="background-color: #eaeaea; color: #B3A5A5;">Voltar</button>
                    <button type="button" class="btn text-white" id="enviarCodigoBtn"
                        style="background-color: #69BCD0;">Enviar Código</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Inserir Código -->
    <div class="modal fade" id="codigoModal" tabindex="-1" aria-labelledby="codigoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-4 rounded-4 shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="codigoModalLabel" style="color: #69BCD0;">Digite o Código</h5>
                </div>
                <div class="modal-body">
                    <label for="codigoInput" class="form-label">Insira o código enviado por e-mail:</label>
                    <input type="text" class="form-control" id="codigoInput" placeholder="Código de verificação">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" id="voltarParaEmailBtn"
                        style="background-color: #eaeaea; color: #B3A5A5;">Voltar</button>
                     <button type="button" class="btn text-white"
                            style="background-color: #69BCD0;">Verificar</button> 
                </div>
            </div>
        </div>
    </div>

<!-- Scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("btnEsqueciSenha").addEventListener("click", function () {
                const emailModal = new bootstrap.Modal(document.getElementById('emailModal'));
                emailModal.show();
            });

            document.getElementById("enviarCodigoBtn").addEventListener("click", function () {
                const emailModalInstance = bootstrap.Modal.getInstance(document.getElementById('emailModal'));
                emailModalInstance.hide();

                const codigoModal = new bootstrap.Modal(document.getElementById('codigoModal'));
                codigoModal.show();
            });

            document.getElementById("voltarParaEmailBtn").addEventListener("click", function () {
                const codigoModalEl = document.getElementById('codigoModal');
                const emailModalEl = document.getElementById('emailModal');

                const codigoModal = bootstrap.Modal.getInstance(codigoModalEl);
                codigoModal.hide();

                codigoModalEl.addEventListener('hidden.bs.modal', function handler() {
                    const emailModal = new bootstrap.Modal(emailModalEl);
                    emailModal.show();
                    codigoModalEl.removeEventListener('hidden.bs.modal', handler);
                });
            });

            document.getElementById("voltarEmailBtn").addEventListener("click", function () {
                const emailModalEl = document.getElementById('emailModal');
                const emailModal = bootstrap.Modal.getInstance(emailModalEl);
                if (emailModal) {
                    emailModal.hide();
                } else {
                    new bootstrap.Modal(emailModalEl).hide();
                }
            });
        });
    </script>

    <!-- Bootstrap Bundle JS (inclui Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("enviarCodigoBtn").addEventListener("click", function () {
        const email = document.getElementById("emailInput").value.trim();

        if (email === "") {
            alert("Digite o e-mail.");
            return;
        }

        fetch("enviar_codigo.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ email: email })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "ok") {
                const emailModalInstance = bootstrap.Modal.getInstance(document.getElementById('emailModal'));
                emailModalInstance.hide();

                const codigoModal = new bootstrap.Modal(document.getElementById('codigoModal'));
                codigoModal.show();
            } else {
                alert(data.mensagem);
            }
        });
    });

    document.querySelector("#codigoModal .btn.btn.text-white").addEventListener("click", function () {
        const codigo = document.getElementById("codigoInput").value.trim();

        fetch("verificar_codigo.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ codigo: codigo })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "ok") {
                window.location.href = "./TCC/assets/paginas/senha.html";
            } else {
                alert(data.mensagem);
            }
        });
    });
});
</script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("loginForm");
            const emailInput = document.getElementById("emailInputLogin");
            const senhaInput = document.getElementById("senhaInputLogin");
            const tipoInput = document.getElementById("tipoUsuario");
            const erroMsg = document.getElementById("erroCamposVazios");

            form.addEventListener("submit", function (event) {
                let hasError = false;
                erroMsg.classList.add("d-none");

                [emailInput, senhaInput, tipoInput].forEach(input => input.classList.remove("input-error"));

                if (emailInput.value.trim() === "") {
                    emailInput.classList.add("input-error");
                    hasError = true;
                }

                if (senhaInput.value.trim() === "") {
                    senhaInput.classList.add("input-error");
                    hasError = true;
                }

                if (tipoInput.value.trim() === "") {
                    tipoInput.classList.add("input-error");
                    hasError = true;
                }

                if (hasError) {
                    erroMsg.classList.remove("d-none");
                    event.preventDefault();
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
