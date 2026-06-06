   <?php
        session_start();
        include("conexao.php");
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
    <div class="container" id="container">

        <!-- lado esquerdo: area de cadastro -->
        <div class="form-container">
            <div class="forms-wrapper">

                <!-- Cadastro Form -->
                <form method="POST" action="Cadastro_mega_store.php" class="account-form sign-up-form" id="formcad">
                    <div class="logo-container">
                        <img src="img/logo.png" alt="Mega Store Logo" class="brand-logo"
                            onerror="this.src='https://via.placeholder.com/150x50?text=MEGA+STORE';this.style.width='150px';">
                    </div>
                    <h2 class="title">Criar nova conta</h2>
                    <p class="subtitle">Junte-se à Mega Store e aproveite ofertas exclusivas.</p>

                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" placeholder="Email" name="mail" id="mailcad" />
                        
                       
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Senha" name="senha" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Confirmar Senha" name="repsenha" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-phone"></i>
                        <input type="tel" placeholder="Telefone" name="tel" />
                    </div>

                    <button type="submit" class="btn solid" name="cadastrar">Cadastrar-se</button>

                    <div class="divider">
                        <span>Já possui uma conta?</span>
                    </div>

                    <button type="button" class="btn outline" id="sign-in-btn">Fazer Login</button>
                </form>

                <!-- Login Form -->
                <form method="POST" action="#" class="account-form sign-in-form" id="formlogin">
                    <div class="logo-container">
                        <img src="img/logo.png" alt="Mega Store Logo" class="brand-logo"
                            onerror="this.src='https://via.placeholder.com/150x50?text=MEGA+STORE';this.style.width='150px';">
                    </div>
                    <h2 class="title">Bem-vindo de volta!</h2>
                    <p class="subtitle">Acesse sua conta e continue comprando com a gente.</p>

                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" placeholder="Email" name="loginmail" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Senha" name="loginsenha" />
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
                    <div class="img-wrapper"><img src="img/img_carrossel_qualidade_1.jpeg   "
                            onerror="this.src='https://images.unsplash.com/photo-1607082349566-187342175e2f?auto=format&fit=crop&q=80&w=800&h=600'"
                            alt="Promo 1"></div>
                    <div class="img-wrapper"><img src="img/img_carrossel_qualidade_2.jpeg"
                            onerror="this.src='https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&q=80&w=800&h=600'"
                            alt="Promo 2"></div>
                    <div class="img-wrapper"><img src="img/img_carrossel_qualidade_3.jpeg"
                            onerror="this.src='https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=800&h=600'"
                            alt="Promo 3"></div>
                    <div class="img-wrapper"><img src="img/carrossel_img4.png"
                            onerror="this.src='https://images.unsplash.com/photo-1555529771-835f59bfc50c?auto=format&fit=crop&q=80&w=800&h=600'"
                            alt="Promo 4"></div>
                    <div class="img-wrapper"><img src="img/carrossel_img5.png"
                            onerror="this.src='https://images.unsplash.com/photo-1472851294608-062f824d29cc?auto=format&fit=crop&q=80&w=800&h=600'"
                            alt="Promo 5"></div>
                </div>
            </div>
        </div>
    </div>


</body>
   <?php
   //CADASTRO USUUUUUUUUUUUU
    $data = date('Y/m/d');
    //echo "$data";
     if(isset($_POST["cadastrar"])){
       $user="Nome Generico da Silva";//$_POST["user"];
       $mail=$_POST["mail"];
       $tel=$_POST["tel"];
       $senha=$_POST["senha"];
       $repsenha=$_POST["repsenha"];
       $erro='';
       if($user==''){//verifica se o nome de usuario não é vazio
         $erro.="Digite seu nome de Usuário<br>";
       }
       if($mail==''){//verifica se o email não é vazio
         $erro.="Digite seu Email<br>";
       }
       if($tel==''){//verifica se o telefone não é vazio
         $erro.="Digite seu Telefone<br>";
       }
       if($senha==''){//verifica se a senha não é vazia
         $erro.="Digite a Senha<br>";
       } 
       if($repsenha==''){//verifica se a repete senha  não é vazia
         $erro.="Repita a Senha<br>";
       }
       if($senha!=$repsenha and $senha!='' and $repsenha!=''){
         $erro.="Senha e Repete Senha são diferentes";
       } 
       //variaveis para verificar se a senha é valida
        $tamanhoMinimo = false;
        $temMaiuscula = false;
        $temMinuscula = false;
        $temNumero = false;
        $temEspecial = false;
        $temEspaco = false;
       for ($i = 0; $i < strlen($senha); $i++) {
    $char = $senha[$i];
    if (8< strlen($senha)) {//verifica se a senha tem no minimo 8 characteres
        $tamanhoMinimo = true;
    } 
    if (ctype_upper($char)) {//Verifica se possui letra maiuscula
        $temMaiuscula = true;
    } 
    if (ctype_lower($char)) {//Verifica se possui letra minuscula
        $temMinuscula = true;
    } 
    if (ctype_digit($char)) {//Verifica se possui numeros
        $temNumero = true;
    } 
    if (!ctype_alnum($char)) {//Verifica se possui characteres especiais
        $temEspecial = true;
    }
    if ($char == " ") {//Verifica se possui letra maiuscula
        $temEspaco = true;
    }
}
      if(!$tamanhoMinimo){
        $erro.="A senha deve conter no mínimo 8 characteres<br>";
      }
      if(!$temMaiuscula){
        $erro.="A senha deve conter no mínimo 1 letra maiuscula<br>";
      }
      if(!$temMinuscula){
        $erro.="A senha deve conter no mínimo 1 letra minuscula<br>";
      }
      if(!$temNumero){
        $erro.="A senha deve conter no mínimo 1 numero<br>";
      }
     // if(!$temEspecial){
     //   $erro.="A senha deve conter no mínimo 1 character especial<br>";
     // }
      if($temEspaco){
        $erro.="A senha não pode conter espaços<br>";
      }
      $sql = "SELECT *
              FROM tb_usuario
              WHERE email = '$mail'";
      $r = mysqli_query($con, $sql);
      if (mysqli_num_rows($r) > 0) {
        $erro.="Este email ja esta cadastrado!<br>";
      }
       if($erro==''){
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO tb_usuario (nome_usuario, email, senha, numero, tipo, data_cadastro, modo_tela) 
                VALUES ('$user', '$mail', '$senha_hash', '$tel', '0', '$data', '0')";
         $r= mysqli_query($con,$sql);
       $_SESSION['id_usuario'] = mysqli_insert_id($con);
         echo"<font color=green size=4>Usuario Cadastrado com Sucesso</font>";
         //header("Location: menu_principal.php");

       }
      else{
       echo"<font color=red size=4>$erro</font>";
     }
     }
     //LOGIN USUUUUUUUUUUUU
     if(isset($_POST["login"])){
                $email=$_POST["loginmail"];
                $senha=$_POST["loginsenha"];
                $erro='';
                if($email==''){
                    $erro.="Digite o email<br>";
                }
                if($senha==''){
                    $erro.="Digite a Senha<br>";
                } 
                if($erro==''){
                    $sql = "SELECT * 
                            FROM tb_usuario 
                            WHERE email = '$email'";
                    $r = mysqli_query($con, $sql);
                    if (mysqli_num_rows($r) > 0) {
                     $usuario = mysqli_fetch_assoc($r);
                        if(password_verify($senha, $usuario["senha"])){  
                            $_SESSION['id_usuario'] = $usuario['id_usuario'];
                            echo "Login realizado com sucesso!";
                            //header("Location: menu_principal.php");
                        }else{
                            $erro.="Senha incorreta!";
                        }
                    } else {
                        $erro.="Email não encontrado!";
                    }
                    if($erro==''){

                    }else{
                        echo"<font color='red' size='4'>$erro</font>";
                    }
                }else{
                    echo"<font color='red' size='4'>$erro</font>";
                }
             }
    ?>
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