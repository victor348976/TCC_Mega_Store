<?php
include "../geral/conexao.php";

$categorias = [];
if ($con) {
    $categoriasSql = mysqli_query($con, 'SELECT id_categoria, nome_categoria FROM tb_categoria ORDER BY nome_categoria');
    if ($categoriasSql) {
        $categorias = mysqli_fetch_all($categoriasSql, MYSQLI_ASSOC);
    }
}

$message = '';
if (isset($_GET['sucesso'])) {
    $message = 'Produto salvo com sucesso.';
}
if (isset($_GET['erro'])) {
    $message = 'Não foi possível salvar o produto.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Cadastro de Produto | Mega Store Admin</title>
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
                <li><a href="cadastro_produto.php" class="on">Novo Produto</a></li>
                <li><a href="editar_produto.php">Editar Produto</a></li>
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
        <h1>Cadastrar Produto</h1>
        <p class="sub">Adicione uma nova peça ou tecido à coleção.</p>
        <div class="divider"></div>

        <?php if ($message): ?>
            <div class="alert-box"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form class="grid" id="f" action="salvar_produto.php" method="post" enctype="multipart/form-data">
            <div>
                <div class="card">
                    <h2>Informações</h2>
                    <div class="f">
                        <label>Nome do produto</label>
                        <input name="nome" required placeholder="Ex.: Vestido Noir Éternel">
                    </div>
                    <div class="row">
                        <div class="f">
                            <label>Categoria</label>
                            <select name="categoria">
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?= (int) $categoria['id_categoria'] ?>"><?= htmlspecialchars($categoria['nome_categoria']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="f">
                            <label>Gênero</label>
                            <select name="genero">
                                <option value="1">Masculino</option>
                                <option value="2">Feminino</option>
                                <option value="3">Infantil</option>
                                <option value="4">Unissex</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="f"><label>Preço (R$)</label><input name="preco" type="number" step="0.01" min="0" required placeholder="0,00"></div>
                        <div class="f"><label>Venda por</label><select name="unidade"><option>Peça</option><option>Metro</option></select></div>
                    </div>
                    <div class="f"><label>Descrição</label><textarea name="descricao" placeholder="Composição, caimento, detalhes..."></textarea></div>
                </div>

                <div class="card">
                    <h2>Estoque e Tamanhos</h2>
                    <div class="f"><label>Quantidade em estoque</label><input name="estoque" type="number" min="0" value="0"></div>
                    <div class="f">
                        <label>Tamanhos disponíveis</label>
                        <div class="chips">
                            <label class="chip"><input type="checkbox" name="tam[]" value="PP"><span>PP</span></label>
                            <label class="chip"><input type="checkbox" name="tam[]" value="P"><span>P</span></label>
                            <label class="chip"><input type="checkbox" name="tam[]" value="M"><span>M</span></label>
                            <label class="chip"><input type="checkbox" name="tam[]" value="G"><span>G</span></label>
                            <label class="chip"><input type="checkbox" name="tam[]" value="GG"><span>GG</span></label>
                            <label class="chip"><input type="checkbox" name="tam[]" value="Único"><span>Único</span></label>
                        </div>
                    </div>
                </div>
            </div>

            <aside>
                <div class="card">
                    <h2>Imagem</h2>
                    <label class="drop" id="drop">Clique para enviar<br>uma imagem<input type="file" name="imagem" accept="image/*" hidden id="img"></label>
                    <div class="f" style="margin-top:24px"><label>Selo</label>
                        <select name="selo">
                            <option value="">Nenhum</option>
                            <option>Novo</option>
                            <option>Exclusivo</option>
                        </select>
                    </div>
                    <div class="switch"><span class="label">Publicar imediatamente</span><label><input type="checkbox" name="ativo" checked><i></i></label></div>
                </div>
                <div class="actions">
                    <button class="btn" type="submit">Salvar Produto</button>
                    <a href="editar_produto.php" class="btn btn--o">Cancelar</a>
                </div>
            </aside>
        </form>
    </main>

    <div class="foot">&copy; 2026 Mega Store. Painel administrativo.</div>
    <script>
        img.onchange = e => {
            const f = e.target.files[0];
            if (f) {
                drop.style.backgroundImage = 'url(' + URL.createObjectURL(f) + ')';
                drop.firstChild.textContent = '';
            }
        }
    </script>
</body>
</html>
