<?php
include "../geral/conexao.php";

$pedidosSql = mysqli_query($con, "
    SELECT v.*, u.nome_usuario
    FROM tb_venda v
    INNER JOIN tb_usuario u ON u.id_usuario = v.id_usuario
    ORDER BY v.data_pedido DESC
");
$pedidos = mysqli_fetch_all($pedidosSql, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Gerenciar Pedidos | Mega Store Admin</title>
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
                <li><a href="gerenciar_pedidos.php" class="on">Pedidos</a></li>
                <li><a href="usuarios.php">Usuários</a></li>
            </ul>
            <span class="user">@ADMIN</span>
            <button class="theme-toggle" type="button" data-theme-toggle aria-label="Ativar modo escuro" aria-pressed="false">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"></path></svg>
                <span data-theme-label>Modo escuro</span>
            </button>
        </div>
    </header>

    <main class="page">
        <span class="label">Vendas</span>
        <h1>Gerenciar Pedidos</h1>
        <p class="sub">Acompanhe e atualize o status de cada pedido.</p>
        <div class="divider"></div>

        <div class="stats">
            <div class="stat"><span class="label">Pendentes</span><b><?php echo count(array_filter($pedidos, fn($pedido) => $pedido['status_venda'] === 'Pendente')); ?></b></div>
            <div class="stat"><span class="label">A enviar</span><b><?php echo count(array_filter($pedidos, fn($pedido) => $pedido['status_venda'] === 'Pago')); ?></b></div>
            <div class="stat"><span class="label">Entregues</span><b><?php echo count(array_filter($pedidos, fn($pedido) => $pedido['status_venda'] === 'Entregue')); ?></b></div>
            <div class="stat"><span class="label">Total</span><b><?= count($pedidos) ?></b></div>
        </div>

        <div class="card">
            <div class="tw">
                <table>
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Alterar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td>#<?= (int) $pedido['id_venda'] ?></td>
                                <td><?= htmlspecialchars($pedido['nome_usuario']) ?></td>
                                <td><?= date('d/m/Y', strtotime($pedido['data_pedido'])) ?></td>
                                <td>R$ <?= number_format((float) $pedido['valor_total'], 2, ',', '.') ?></td>
                                <td>
                                    <span class="badge <?= $pedido['status_venda'] === 'Pendente' ? 'b-warn' : ($pedido['status_venda'] === 'Entregue' ? 'b-mut' : 'b-ok') ?>"><?= htmlspecialchars($pedido['status_venda']) ?></span>
                                </td>
                                <td>
                                    <form action="atualizar_pedido.php" method="post" class="inline-form">
                                        <input type="hidden" name="id_venda" value="<?= (int) $pedido['id_venda'] ?>">
                                        <select name="status_venda">
                                            <option value="Pendente" <?= $pedido['status_venda'] === 'Pendente' ? 'selected' : '' ?>>Pendente</option>
                                            <option value="Pago" <?= $pedido['status_venda'] === 'Pago' ? 'selected' : '' ?>>Pago</option>
                                            <option value="Enviado" <?= $pedido['status_venda'] === 'Enviado' ? 'selected' : '' ?>>Enviado</option>
                                            <option value="Entregue" <?= $pedido['status_venda'] === 'Entregue' ? 'selected' : '' ?>>Entregue</option>
                                            <option value="Cancelado" <?= $pedido['status_venda'] === 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                        </select>
                                        <button class="btn btn--o" type="submit">Salvar</button>
                                    </form>
                                </td>
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
