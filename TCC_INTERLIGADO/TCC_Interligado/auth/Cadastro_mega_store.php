<?php
        require_once __DIR__ . '/../geral/auth.php';
        $sessao = current_user();
        if ($sessao) {
            header('Location: ' . ((int) $sessao['tipo'] === 1 ? '../admin/index.php' : '../usuario/perfil.php'));
            exit;
        }

        $user = '';
        $mail = '';
        $userErro = '';
        $tel = '';
        $senha = '';
        $repsenha = '';
        $email = '';
        $mailErro = '';
        $telErro = '';
        $senhaErro = '';
        $repsenhaErro = '';
        $loginEmailErro = '';
        $loginSenhaErro = '';

        $alerta = array(
            'tipo' => '',
            'texto' => ''
        );
        $formulario = isset($_POST['login']) ? 'login' : 'cadastro';
   //CADASTRO USUUUUUUUUUUUU
    $data = date('Y-m-d');
     if(isset($_POST["cadastrar"])){
       $user = trim($_POST["user"] ?? '');
       $mail = strtolower(trim($_POST["mail"] ?? ''));
       $tel = preg_replace('/\D+/', '', $_POST["tel"] ?? '');
       $senha = isset($_POST["senha"]) ? $_POST["senha"] : '';
       $repsenha = isset($_POST["repsenha"]) ? $_POST["repsenha"] : '';

       $userErro = '';
       $mailErro = '';
       $telErro = '';
       $senhaErro = '';
       $repsenhaErro = '';

       if($user === '' || mb_strlen($user) > 255){
         $userErro = 'Informe seu nome';
       }
       if(!filter_var($mail, FILTER_VALIDATE_EMAIL) || mb_strlen($mail) > 255){
         $mailErro = 'Digite um email válido';
       }
       if(strlen($tel) < 8 || strlen($tel) > 10 || (int) $tel > 2147483647){
         $telErro = 'Digite um telefone válido com 8 a 10 números';
       }
       if($senha == ''){
         $senhaErro = 'Digite a senha';
       }
       if($repsenha == ''){
         $repsenhaErro = 'Repita a senha';
       }

       if($senha != $repsenha && $senha != '' && $repsenha != ''){
         $repsenhaErro = 'As senhas não coincidem';
       }

       //variaveis para verificar se a senha é valida
        $tamanhoMinimo = false;
        $temMaiuscula = false;
        $temMinuscula = false;
        $temNumero = false;
        $temEspaco = false;
       for ($i = 0; $i < strlen($senha); $i++) {
          $char = $senha[$i];
          if (strlen($senha) >= 8) {
              $tamanhoMinimo = true;
          }
          if (ctype_upper($char)) {
              $temMaiuscula = true;
          }
          if (ctype_lower($char)) {
              $temMinuscula = true;
          }
          if (ctype_digit($char)) {
              $temNumero = true;
          }
          if ($char == " ") {
              $temEspaco = true;
          }
       }

      if($senha != '' && !$tamanhoMinimo){
        $senhaErro = 'A senha deve ter no mínimo 8 caracteres';
      }
      if($senha != '' && !$temMaiuscula){
        $senhaErro = 'A senha deve ter pelo menos 1 letra maiúscula';
      }
      if($senha != '' && !$temMinuscula){
        $senhaErro = 'A senha deve ter pelo menos 1 letra minúscula';
      }
      if($senha != '' && !$temNumero){
        $senhaErro = 'A senha deve ter pelo menos 1 número';
      }
      if($temEspaco){
        $senhaErro = 'A senha não pode conter espaços';
      }

      $stmtEmail = mysqli_prepare($con, 'SELECT id_usuario FROM tb_usuario WHERE email = ? LIMIT 1');
      mysqli_stmt_bind_param($stmtEmail, 's', $mail);
      mysqli_stmt_execute($stmtEmail);
      if ($mail != '' && mysqli_num_rows(mysqli_stmt_get_result($stmtEmail)) > 0) {
        $mailErro = 'Este email já está cadastrado';
      }

       if($userErro == '' && $mailErro == '' && $telErro == '' && $senhaErro == '' && $repsenhaErro == ''){
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmtCadastro = mysqli_prepare($con, 'INSERT INTO tb_usuario (nome_usuario, email, senha, numero, tipo, data_cadastro, modo_tela) VALUES (?, ?, ?, ?, 0, ?, 0)');
        mysqli_stmt_bind_param($stmtCadastro, 'sssis', $user, $mail, $senha_hash, $tel, $data);
        if (mysqli_stmt_execute($stmtCadastro)) {
            session_regenerate_id(true);
            $_SESSION['id_usuario'] = mysqli_insert_id($con);
            $_SESSION['usuario_logado'] = true;
            header('Location: ../usuario/perfil.php');
            exit;
        }
        $alerta['tipo'] = 'erro';
        $alerta['texto'] = 'Não foi possível criar a conta. Tente novamente.';
       }
     }
     //LOGIN USUUUUUUUUUUUU
     if(isset($_POST["login"])){
                $email = isset($_POST["loginmail"]) ? trim($_POST["loginmail"]) : '';
                $senha = isset($_POST["loginsenha"]) ? $_POST["loginsenha"] : '';
                $loginEmailErro = '';
                $loginSenhaErro = '';

                if($email == ''){
                    $loginEmailErro = 'Digite o email';
                }
                if($senha == ''){
                    $loginSenhaErro = 'Digite a senha';
                }

                if($loginEmailErro == '' && $loginSenhaErro == ''){
                    $email = strtolower($email);
                    $stmtLogin = mysqli_prepare($con, 'SELECT id_usuario, senha, tipo FROM tb_usuario WHERE email = ? LIMIT 1');
                    mysqli_stmt_bind_param($stmtLogin, 's', $email);
                    mysqli_stmt_execute($stmtLogin);
                    $r = mysqli_stmt_get_result($stmtLogin);
                    if (mysqli_num_rows($r) > 0) {
                     $usuario = mysqli_fetch_assoc($r);
                        if(password_verify($senha, $usuario["senha"])){  
                            session_regenerate_id(true);
                            $_SESSION['id_usuario'] = $usuario['id_usuario'];
                            $_SESSION['usuario_logado'] = true;
                            if ((int) $usuario['tipo'] === 1) {
                                header('Location: ../admin/index.php');
                            } else {
                                header('Location: ../usuario/perfil.php');
                            }
                            exit;
                        }else{
                            $loginSenhaErro = 'Senha incorreta';
                        }
                    } else {
                        $loginEmailErro = 'Email não encontrado';
                    }
                }

                if($loginEmailErro != '' || $loginSenhaErro != ''){
                    $alerta['tipo'] = 'erro';
                    $alerta['texto'] = $loginEmailErro != '' ? $loginEmailErro : $loginSenhaErro;
                }
             }
    ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mega Store - Login e Cadastro</title>
    <!-- Importing Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Importing FontAwesome -->
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="Cadastro_mega_store.css">
</head>

<body>
    <div class="container <?= $formulario === 'login' ? 'sign-in-mode' : '' ?>" id="container">

        <!-- lado esquerdo: area de cadastro -->
        <div class="form-container">
            <div class="forms-wrapper">

                <!-- Cadastro Form -->
                <form method="POST" action="" class="account-form sign-up-form" id="formcad">
                    <div class="logo-container">
                        <img src="../img/logo.png" alt="Mega Store Logo" class="brand-logo"
                            onerror="this.src='https://via.placeholder.com/150x50?text=MEGA+STORE';this.style.width='150px';">
                    </div>
                    <h2 class="title">Criar nova conta</h2>
                    <p class="subtitle">Junte-se à Mega Store e aproveite ofertas exclusivas.</p>

                    <div class="input-wrap">
                        <div class="input-field">
                            <i class="fas fa-user"></i>
                            <input type="text" placeholder="Nome completo" name="user" maxlength="255" autocomplete="name" value="<?= htmlspecialchars($user, ENT_QUOTES, 'UTF-8') ?>" required />
                        </div>
                        <?php if (!empty($userErro)): ?>
                            <div class="zara-field-error" role="alert"><?= htmlspecialchars($userErro, ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="input-wrap">
                        <div class="input-field">
                            <i class="fas fa-envelope"></i>
                            <input type="email" placeholder="Email" name="mail" id="mailcad" autocomplete="email" value="<?= htmlspecialchars($mail, ENT_QUOTES, 'UTF-8') ?>" required />
                        </div>
                        <?php if (!empty($mailErro)): ?>
                            <div class="zara-field-error" role="alert"><?= htmlspecialchars($mailErro) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="input-wrap">
                        <div class="input-field">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Senha" name="senha" autocomplete="new-password" required />
                        </div>
                        <?php if (!empty($senhaErro)): ?>
                            <div class="zara-field-error" role="alert"><?= htmlspecialchars($senhaErro) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="input-wrap">
                        <div class="input-field">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Confirmar Senha" name="repsenha" autocomplete="new-password" required />
                        </div>
                        <?php if (!empty($repsenhaErro)): ?>
                            <div class="zara-field-error" role="alert"><?= htmlspecialchars($repsenhaErro) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="input-wrap">
                        <div class="input-field">
                            <i class="fas fa-phone"></i>
                            <input type="tel" placeholder="Telefone" name="tel" autocomplete="tel" inputmode="numeric" value="<?= htmlspecialchars($tel, ENT_QUOTES, 'UTF-8') ?>" required />
                        </div>
                        <?php if (!empty($telErro)): ?>
                            <div class="zara-field-error" role="alert"><?= htmlspecialchars($telErro) ?></div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn solid" name="cadastrar">Cadastrar-se</button>

                    <div class="divider">
                        <span>Já possui uma conta?</span>
                    </div>

                    <button type="button" class="btn outline" id="sign-in-btn">Fazer Login</button>
                </form>

                <!-- Login Form -->
                <form method="POST" action="" class="account-form sign-in-form" id="formlogin">
                    <div class="logo-container">
                        <img src="../img/logo.png" alt="Mega Store Logo" class="brand-logo"
                            onerror="this.src='https://via.placeholder.com/150x50?text=MEGA+STORE';this.style.width='150px';">
                    </div>
                    <h2 class="title">Bem-vindo de volta!</h2>
                    <p class="subtitle">Acesse sua conta e continue comprando com a gente.</p>

                    <div class="input-wrap">
                        <div class="input-field">
                            <i class="fas fa-envelope"></i>
                            <input type="email" placeholder="Email" name="loginmail" autocomplete="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required />
                        </div>
                        <?php if (!empty($loginEmailErro)): ?>
                            <div class="zara-field-error" role="alert"><?= htmlspecialchars($loginEmailErro) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="input-wrap">
                        <div class="input-field">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Senha" name="loginsenha" autocomplete="current-password" required />
                        </div>
                        <?php if (!empty($loginSenhaErro)): ?>
                            <div class="zara-field-error" role="alert"><?= htmlspecialchars($loginSenhaErro) ?></div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn solid" name="login">Fazer Login</button>

                    <div class="divider">
                        <span>Não possui uma conta?</span>
                    </div>

                    <button type="button" class="btn outline" id="sign-up-btn">Cadastrar-se</button>
                </form>

            </div>
        </div>

        <!-- Right Side: Carousel Area -->
        <div class="carousel-container">
            <div class="carousel-content">
                <div class="carousel-overlay"></div>
                <div class="carousel-text">
                    <h2>Descubra as<br>Melhores Ofertas</h2>
                    <p>Qualidade, segurança e tudo que você precisa em um só lugar.</p>
                </div>
                <!-- div-carrossel -->
                <div class="vertical-carousel">
                    <!-- imagens do carrossel -->
                    <div class="img-wrapper"><img src="../img/img_carrossel_qualidade_1.jpeg"
                            onerror="this.src='https://images.unsplash.com/photo-1607082349566-187342175e2f?auto=format&fit=crop&q=80&w=800&h=600'"
                            alt="Promo 1"></div>
                    <div class="img-wrapper"><img src="../img/img_carrossel_qualidade_2.jpeg"
                            onerror="this.src='https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&q=80&w=800&h=600'"
                            alt="Promo 2"></div>
                    <div class="img-wrapper"><img src="../img/img_carrossel_qualidade_3.jpeg"
                            onerror="this.src='https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=800&h=600'"
                            alt="Promo 3"></div>
                    <div class="img-wrapper"><img src="../img/carrossel_img4.png"
                            onerror="this.src='https://images.unsplash.com/photo-1555529771-835f59bfc50c?auto=format&fit=crop&q=80&w=800&h=600'"
                            alt="Promo 4"></div>
                    <div class="img-wrapper"><img src="../img/carrossel_img5.png"
                            onerror="this.src='https://images.unsplash.com/photo-1472851294608-062f824d29cc?auto=format&fit=crop&q=80&w=800&h=600'"
                            alt="Promo 5"></div>
                </div>
            </div>
        </div>
    </div>


</body>
   
<script>
    const signInBtn = document.querySelector("#sign-in-btn");
    const signUpBtn = document.querySelector("#sign-up-btn");
    const container = document.querySelector("#container");

    signInBtn.addEventListener("click", () => {
        container.classList.add("sign-in-mode");
    });

    signUpBtn.addEventListener("click", () => {
        container.classList.remove("sign-in-mode");
    });
</script>

</html>