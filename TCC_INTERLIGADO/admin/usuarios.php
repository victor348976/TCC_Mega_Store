<?php
include "../geral/conexao.php";

$usuariosSql = mysqli_query($con, "SELECT id_usuario, nome_usuario, email, numero, tipo, data_cadastro FROM tb_usuario ORDER BY id_usuario DESC");
$usuarios = mysqli_fetch_all($usuariosSql, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Usuários | Mega Store Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
    <script src="admin.js" defer></script>
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <a href="../home/Tl_principal.html" class="logo"><b>MEGA</b><span>STORE</span></a>
            <ul class="nav">
                <li><a href="cadastro_produto.php">Novo Produto</a></li>
                <li><a href="editar_produto.php">Editar Produto</a></li>
                <li><a href="gerenciar_pedidos.php">Pedidos</a></li>
                <li><a href="usuarios.php" class="on">Usuários</a></li>
            </ul>
            <span class="user">@ADMIN</span>
            <button class="theme-toggle" type="button" data-theme-toggle aria-label="Ativar modo escuro" aria-pressed="false">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"></path></svg>
                <span data-theme-label>Modo escuro</span>
            </button>
        </div>
    </header>

    <main class="page">
        <span class="label">Clientes</span>
        <h1>Usuários cadastrados</h1>
        <p class="sub">Acompanhe os clientes e suas informações básicas.</p>
        <div class="divider"></div>

        <div class="card">
            <div class="tw">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Tipo</th>
                            <th>Cadastro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td>#<?= (int) $usuario['id_usuario'] ?></td>
                                <td><?= htmlspecialchars($usuario['nome_usuario']) ?></td>
                                <td><?= htmlspecialchars($usuario['email']) ?></td>
                                <td><?= htmlspecialchars($usuario['numero']) ?></td>
                                <td><?= $usuario['tipo'] == 1 ? 'Admin' : 'Cliente' ?></td>
                                <td><?= date('d/m/Y', strtotime($usuario['data_cadastro'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="foot">&copy; 2026 Mega Store. Painel administrativo.</div>
</body>
</html>
