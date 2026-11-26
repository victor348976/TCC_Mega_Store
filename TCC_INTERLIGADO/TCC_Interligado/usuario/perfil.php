<?php
require_once __DIR__ . '/../geral/auth.php';

$usuario = require_login();
$csrfToken = $_SESSION['csrf_perfil'] ?? '';
if ($csrfToken === '') {
    $csrfToken = bin2hex(random_bytes(32));
    $_SESSION['csrf_perfil'] = $csrfToken;
}
$erro = '';
$sucesso = isset($_GET['salvo']) ? 'Dados atualizados com sucesso.' : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tokenEnviado = $_POST['token'] ?? '';
    if (!is_string($tokenEnviado) || !hash_equals($csrfToken, $tokenEnviado)) {
        http_response_code(400);
        exit('Solicitação inválida. Atualize a página e tente novamente.');
    }

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = preg_replace('/\D+/', '', $_POST['telefone'] ?? '');
    $senhaAtual = $_POST['senha_atual'] ?? '';
    $novaSenha = $_POST['nova_senha'] ?? '';
    $confirmacaoSenha = $_POST['confirmacao_senha'] ?? '';

    if ($nome === '' || mb_strlen($nome) > 255) {
        $erro = 'Informe um nome com até 255 caracteres.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 255) {
        $erro = 'Informe um e-mail válido.';
    } elseif (strlen($telefone) < 8 || strlen($telefone) > 10 || (int) $telefone > 2147483647) {
        $erro = 'Informe um telefone com 8 a 10 dígitos.';
    } else {
        $stmt = mysqli_prepare($con, 'SELECT id_usuario FROM tb_usuario WHERE email = ? AND id_usuario <> ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 'si', $email, $usuario['id_usuario']);
        mysqli_stmt_execute($stmt);
        $emailDuplicado = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if ($emailDuplicado) {
            $erro = 'Este e-mail já está sendo usado por outra conta.';
        } elseif ($novaSenha !== '') {
            $stmtSenha = mysqli_prepare($con, 'SELECT senha FROM tb_usuario WHERE id_usuario = ? LIMIT 1');
            mysqli_stmt_bind_param($stmtSenha, 'i', $usuario['id_usuario']);
            mysqli_stmt_execute($stmtSenha);
            $credencial = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtSenha));

            if (!$credencial || !password_verify($senhaAtual, $credencial['senha'])) {
                $erro = 'Confirme sua senha atual para alterá-la.';
            } elseif ($novaSenha !== $confirmacaoSenha || strlen($novaSenha) < 8 || !preg_match('/[A-Z]/', $novaSenha) || !preg_match('/[a-z]/', $novaSenha) || !preg_match('/[0-9]/', $novaSenha) || preg_match('/\s/', $novaSenha)) {
                $erro = 'A nova senha deve ter ao menos 8 caracteres, maiúscula, minúscula e número; as confirmações devem coincidir.';
            }
        }

        if ($erro === '') {
            if ($novaSenha !== '') {
                $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
                $stmt = mysqli_prepare($con, 'UPDATE tb_usuario SET nome_usuario = ?, email = ?, numero = ?, senha = ? WHERE id_usuario = ?');
                mysqli_stmt_bind_param($stmt, 'ssisi', $nome, $email, $telefone, $senhaHash, $usuario['id_usuario']);
            } else {
                $stmt = mysqli_prepare($con, 'UPDATE tb_usuario SET nome_usuario = ?, email = ?, numero = ? WHERE id_usuario = ?');
                mysqli_stmt_bind_param($stmt, 'ssii', $nome, $email, $telefone, $usuario['id_usuario']);
            }

            if (mysqli_stmt_execute($stmt)) {
                header('Location: perfil.php?salvo=1');
                exit;
            }

            $erro = 'Não foi possível salvar seu perfil. Tente novamente.';
        }
    }
}

$usuario = current_user();
$isAdmin = (int) $usuario['tipo'] === 1;
$pedidos = [];
$stmt = mysqli_prepare($con, 'SELECT id_venda, data_pedido, status_venda, valor_total FROM tb_venda WHERE id_usuario = ? ORDER BY data_pedido DESC, id_venda DESC');
mysqli_stmt_bind_param($stmt, 'i', $usuario['id_usuario']);
mysqli_stmt_execute($stmt);
$resultadoPedidos = mysqli_stmt_get_result($stmt);
while ($pedido = mysqli_fetch_assoc($resultadoPedidos)) {
    $pedidos[] = $pedido;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Meu perfil | Mega Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admin/admin.css">
    <script src="../admin/admin.js" defer></script>
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <a href="../home/Tl_principal.php" class="logo"><b>MEGA</b><span>STORE</span></a>
            <ul class="nav">
                <li><a href="../mega-store/index.html">Loja</a></li>
                <?php if ($isAdmin): ?>
                    <li><a href="../admin/index.php">Painel administrativo</a></li>
                <?php endif; ?>
                <li><a href="perfil.php" class="on">Meu perfil</a></li>
            </ul>
            <span class="user"><?= htmlspecialchars($usuario['nome_usuario'], ENT_QUOTES, 'UTF-8') ?></span>
            <form action="../auth/sair.php" method="post">
                <button class="btn btn--o" type="submit">Sair</button>
            </form>
        </div>
    </header>

    <main class="page">
        <span class="label"><?= $isAdmin ? 'Conta administrativa' : 'Minha conta' ?></span>
        <h1>Meu perfil</h1>
        <p class="sub">Atualize seus dados e acompanhe seus pedidos.</p>
        <div class="divider"></div>
        <?php if (isset($_GET['acesso'])): ?>
            <p class="alert-box" role="alert">Esta área é exclusiva para administradores.</p>
        <?php endif; ?>
        <?php if ($erro !== ''): ?>
            <p class="alert-box" role="alert"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></p>
        <?php elseif ($sucesso !== ''): ?>
            <p class="alert-box" role="status"><?= htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <div class="grid">
            <section class="card">
                <h2>Dados da conta</h2>
                <form method="post" action="perfil.php">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <div class="f">
                        <label for="nome">Nome</label>
                        <input id="nome" name="nome" maxlength="255" value="<?= htmlspecialchars($usuario['nome_usuario'], ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div class="f">
                        <label for="email">E-mail</label>
                        <input id="email" name="email" type="email" maxlength="255" value="<?= htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div class="f">
                        <label for="telefone">Telefone</label>
                        <input id="telefone" name="telefone" type="tel" inputmode="numeric" pattern="[0-9]{8,10}" maxlength="10" value="<?= htmlspecialchars((string) ($usuario['numero'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <h2>Alterar senha (opcional)</h2>
                    <div class="f">
                        <label for="senha_atual">Senha atual</label>
                        <input id="senha_atual" name="senha_atual" type="password" autocomplete="current-password">
                    </div>
                    <div class="row">
                        <div class="f">
                            <label for="nova_senha">Nova senha</label>
                            <input id="nova_senha" name="nova_senha" type="password" autocomplete="new-password">
                        </div>
                        <div class="f">
                            <label for="confirmacao_senha">Confirmar nova senha</label>
                            <input id="confirmacao_senha" name="confirmacao_senha" type="password" autocomplete="new-password">
                        </div>
                    </div>
                    <p class="note">Tipo de conta: <?= $isAdmin ? 'Administrador' : 'Cliente' ?> · Cadastro em <?= date('d/m/Y', strtotime($usuario['data_cadastro'])) ?></p>
                    <div class="actions"><button class="btn" type="submit">Salvar alterações</button></div>
                </form>
            </section>

            <?php if ($isAdmin): ?>
                <section class="card">
                    <h2>Administração</h2>
                    <p>Gerencie o catálogo, os pedidos e os usuários da loja.</p>
                    <div class="actions">
                        <a class="btn" href="../admin/index.php">Abrir painel</a>
                        <a class="btn btn--o" href="../admin/usuarios.php">Usuários</a>
                    </div>
                </section>
            <?php endif; ?>

            <section class="card">
                <h2>Meus pedidos</h2>
                <?php if (!$pedidos): ?>
                    <p>Você ainda não possui pedidos registrados.</p>
                    <div class="actions"><a class="btn" href="../mega-store/index.html">Explorar a loja</a></div>
                <?php else: ?>
                    <div class="tw">
                        <table>
                            <thead><tr><th>Pedido</th><th>Data</th><th>Total</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php foreach ($pedidos as $pedido): ?>
                                    <tr>
                                        <td>#<?= (int) $pedido['id_venda'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($pedido['data_pedido'])) ?></td>
                                        <td>R$ <?= number_format((float) $pedido['valor_total'], 2, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($pedido['status_venda'], ENT_QUOTES, 'UTF-8') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>
    <div class="foot">&copy; 2026 Mega Store.</div>
</body>
</html>
