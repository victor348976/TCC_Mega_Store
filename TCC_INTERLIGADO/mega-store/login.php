<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Acesse sua conta Mega Store." />
    <title>Entrar | Mega Store</title>
    <link rel="stylesheet" href="css/style.css" />
    <script defer src="js/produtos.js"></script>
    <script defer src="js/script.js"></script>
    <script defer src="js/login.js"></script>
  </head>
  <body data-page="login">
    <div data-site-header></div>
    <main class="auth-wrap">
      <section class="auth-panel">
        <span class="eyebrow auth-eyebrow">Bem-vinda(o) de volta</span>
        <h1>Entrar</h1>
        <p>Acesse sua conta Mega Store.</p>
        <form id="login-form">
          <div class="form-field">
            <label for="email">E-mail</label
            ><input
              id="email"
              name="email"
              type="email"
              autocomplete="email"
              required
            />
          </div>
          <div class="form-field">
            <label for="senha">Senha</label
            ><input
              id="senha"
              name="senha"
              type="password"
              autocomplete="current-password"
              required
            />
          </div>
          <button class="button button-dark" type="submit">Entrar</button>
          <p class="form-message" aria-live="polite"></p>
        </form>
        <p class="auth-alternative">
          Ainda não possui uma conta? <a href="cadastro.html">Cadastre-se</a>
        </p>
      </section>
    </main>
    <div data-site-footer></div>
  </body>
</html>
