function readUsers() {
  try {
    return JSON.parse(localStorage.getItem("mega-usuarios") || "[]");
  } catch {
    return [];
  }
}

function showFormMessage(form, message, isError = false) {
  const messageElement = form.querySelector(".form-message");
  messageElement.textContent = message;
  messageElement.style.color = isError ? "#9b2929" : "#333";
}

function handleLogin(form) {
  const formData = new FormData(form);
  const email = String(formData.get("email")).trim().toLocaleLowerCase("pt-BR");
  const password = String(formData.get("senha"));
  const user = readUsers().find((savedUser) => {
    return savedUser.email === email && savedUser.senha === password;
  });

  if (!user) {
    showFormMessage(form, "E-mail ou senha não conferem.", true);
    return;
  }

  localStorage.setItem(
    "mega-sessao",
    JSON.stringify({
      nome: user.nome,
      email: user.email,
    })
  );
  showFormMessage(form, `Bem-vinda(o), ${user.nome}. Sua sessão foi iniciada.`);
  form.reset();
}

function handleRegistration(form) {
  const formData = new FormData(form);
  const name = String(formData.get("nome")).trim();
  const email = String(formData.get("email")).trim().toLocaleLowerCase("pt-BR");
  const password = String(formData.get("senha"));
  const confirmation = String(formData.get("confirmacao"));

  if (password.length < 8) {
    showFormMessage(form, "Use uma senha com pelo menos 8 caracteres.", true);
    return;
  }

  if (password !== confirmation) {
    showFormMessage(form, "As senhas não são iguais.", true);
    return;
  }

  const users = readUsers();
  if (users.some((user) => user.email === email)) {
    showFormMessage(form, "Já existe uma conta com esse e-mail.", true);
    return;
  }

  users.push({ nome: name, email, senha: password });
  localStorage.setItem("mega-usuarios", JSON.stringify(users));
  localStorage.setItem("mega-sessao", JSON.stringify({ nome: name, email }));
  form.reset();
  showFormMessage(form, "Conta criada. Você já está conectada(o).");
}

function initializeAuthentication() {
  const loginForm = document.querySelector("#login-form");
  const registrationForm = document.querySelector("#register-form");

  if (loginForm) {
    loginForm.addEventListener("submit", (event) => {
      event.preventDefault();
      handleLogin(loginForm);
    });
  }

  if (registrationForm) {
    registrationForm.addEventListener("submit", (event) => {
      event.preventDefault();
      handleRegistration(registrationForm);
    });
  }
}

document.addEventListener("DOMContentLoaded", initializeAuthentication);
