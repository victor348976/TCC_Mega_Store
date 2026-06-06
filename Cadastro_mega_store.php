   

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