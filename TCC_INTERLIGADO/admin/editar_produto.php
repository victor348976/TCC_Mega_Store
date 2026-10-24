<?php
include "../geral/conexao.php";

$categoriasSql = mysqli_query($con, 'SELECT id_categoria, nome_categoria FROM tb_categoria ORDER BY nome_categoria');
$categorias = $categoriasSql ? mysqli_fetch_all($categoriasSql, MYSQLI_ASSOC) : [];

$idProduto = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($idProduto > 0) {
    $produtoSql = mysqli_query($con, "SELECT * FROM tb_produto WHERE id_produto = $idProduto LIMIT 1");
    $produto = mysqli_fetch_assoc($produtoSql);
} else {
    $produtoSql = mysqli_query($con, "SELECT * FROM tb_produto ORDER BY id_produto DESC LIMIT 1");
    $produto = mysqli_fetch_assoc($produtoSql);
    if ($produto) {
        $idProduto = (int) $produto['id_produto'];
    }
}

$produtosSql = mysqli_query($con, "SELECT * FROM tb_produto ORDER BY id_produto DESC");
$produtos = mysqli_fetch_all($produtosSql, MYSQLI_ASSOC);

$mensagem = '';
if (isset($_GET['sucesso'])) {
    $mensagem = 'Produto atualizado com sucesso.';
}
if (isset($_GET['erro'])) {
    $mensagem = 'Não foi possível atualizar o produto.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Editar Produto | Mega Store Admin</title>
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
                <li><a href="editar_produto.php" class="on">Editar Produto</a></li>
                <li><a href="gerenciar_pedidos.php">Pedidos</a></li>
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
        <span class="label">Catálogo</span>
        <h1>Editar Produto</h1>
        <p class="sub">Atualize os dados ou inative a peça do catálogo.</p>
        <div class="divider"></div>

        <?php if ($mensagem): ?>
            <div class="alert-box"><?= htmlspecialchars($mensagem) ?></div>
        <?php endif; ?>

        <div class="card" style="margin-bottom: 24px;">
            <h2>Produtos cadastrados</h2>
            <div class="lista-produtos">
                <?php foreach ($produtos as $item): ?>
                    <a class="produto-item <?= $item['id_produto'] == $idProduto ? 'ativo' : '' ?>" href="editar_produto.php?id=<?= (int) $item['id_produto'] ?>">
                        #<?= (int) $item['id_produto'] ?> - <?= htmlspecialchars($item['nome_produto']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ($produto): ?>
            <form class="grid" action="atualizar_produto.php" method="post" id="f">
                <input type="hidden" name="id_produto" value="<?= (int) $produto['id_produto'] ?>">
                <div>
                    <div class="card">
                        <h2>Informações</h2>
                        <div class="f"><label>Nome do produto</label><input name="nome" value="<?= htmlspecialchars($produto['nome_produto']) ?>"></div>
                        <div class="row">
                            <div class="f"><label>Categoria</label>
                                <select name="categoria">
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?= (int) $categoria['id_categoria'] ?>" <?= (int) $produto['id_categoria'] === (int) $categoria['id_categoria'] ? 'selected' : '' ?>><?= htmlspecialchars($categoria['nome_categoria']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="f"><label>Gênero</label>
                                <select name="genero">
                                    <option value="1" <?= $produto['id_genero'] == 1 ? 'selected' : '' ?>>Masculino</option>
                                    <option value="2" <?= $produto['id_genero'] == 2 ? 'selected' : '' ?>>Feminino</option>
                                    <option value="3" <?= $produto['id_genero'] == 3 ? 'selected' : '' ?>>Infantil</option>
                                    <option value="4" <?= $produto['id_genero'] == 4 ? 'selected' : '' ?>>Unissex</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="f"><label>Preço (R$)</label><input name="preco" type="number" step="0.01" value="<?= number_format((float) $produto['preco'], 2, '.', '') ?>"></div>
                            <div class="f"><label>Estoque</label>
                                <?php
                                $variacaoSql = mysqli_query($con, "SELECT SUM(estoque) AS total FROM tb_variacao_produto WHERE id_produto = " . (int)$produto['id_produto']);
                                $variacao = mysqli_fetch_assoc($variacaoSql);
                                $estoqueTotal = isset($variacao['total']) ? (int) $variacao['total'] : 0;
                                ?>
                                <input name="estoque" type="number" value="<?= $estoqueTotal ?>">
                            </div>
                        </div>
                        <div class="f"><label>Descrição</label><textarea name="descricao"><?= htmlspecialchars($produto['descricao_produto']) ?></textarea></div>
                    </div>
                </div>

                <aside>
                    <div class="card">
                        <h2>Status</h2>
                        <div class="prev">
                            <div><b style="font-weight:500"><?= htmlspecialchars($produto['nome_produto']) ?></b><br><span class="badge <?= $produto['ativo'] ? 'b-ok' : 'b-mut' ?>"><?= $produto['ativo'] ? 'Ativo' : 'Inativo' ?></span></div>
                        </div>
                        <div class="switch"><span>Produto ativo na loja</span><label><input type="checkbox" name="ativo" value="1" <?= $produto['ativo'] ? 'checked' : '' ?>><i></i></label></div>
                    </div>
                    <div class="actions">
                        <button class="btn" type="submit">Salvar Alterações</button>
                        <a href="cadastro_produto.php" class="btn btn--o">Novo</a>
                    </div>
                </aside>
            </form>
        <?php endif; ?>
    </main>

    <div class="foot">&copy; 2026 Mega Store. Painel administrativo.</div>
</body>
</html>
