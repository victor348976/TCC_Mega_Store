<?php
require_once __DIR__ . '/../geral/auth.php';
require_admin();

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
$produtos = $produtosSql ? mysqli_fetch_all($produtosSql, MYSQLI_ASSOC) : [];

$variacoes = [];
if ($produto) {
    $variacoesSql = mysqli_query(
        $con,
        "SELECT vp.id_variacao, vp.cor, vp.tamanho, vp.estoque,
                (SELECT ip.caminho_imagem FROM tb_imagem_produto ip WHERE ip.id_variacao = vp.id_variacao ORDER BY ip.id_imagem LIMIT 1) AS caminho_imagem
         FROM tb_variacao_produto vp
         WHERE vp.id_produto = $idProduto
         ORDER BY vp.cor, vp.tamanho, vp.id_variacao"
    );
    $variacoes = $variacoesSql ? mysqli_fetch_all($variacoesSql, MYSQLI_ASSOC) : [];
}

$mensagem = '';
if (isset($_GET['sucesso'])) {
    $mensagem = 'Produto atualizado com sucesso.';
}
if (isset($_GET['excluida'])) {
    $mensagem = 'Variação excluída com sucesso.';
}
if (isset($_GET['limpeza'])) {
    $excluidas = (int) ($_GET['limpeza'] ?? 0);
    $preservadas = (int) ($_GET['preservadas'] ?? 0);
    $mensagem = $excluidas . ' variação(ões) sem estoque excluída(s). ' . $preservadas . ' preservada(s) por estar(em) vinculada(s) a carrinho ou pedido.';
}
if (isset($_GET['erro']) && $_GET['erro'] === 'variacao_em_uso') {
    $mensagem = 'Esta variação está vinculada a um carrinho ou pedido e não pode ser excluída.';
} elseif (isset($_GET['erro'])) {
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
            <a href="../home/Tl_principal.php" class="logo"><b>MEGA</b><span>STORE</span></a>
            <ul class="nav">
                <li><a href="cadastro_produto.php">Novo Produto</a></li>
                <li><a href="editar_produto.php" class="on">Editar Produto</a></li>
                <li><a href="gerenciar_pedidos.php">Pedidos</a></li>
                <li><a href="usuarios.php">Usuários</a></li>
            </ul>
            <a class="user" href="../usuario/perfil.php">Meu perfil</a>
            <form action="../auth/sair.php" method="post"><button class="btn btn--o" type="submit">Sair</button></form>
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
            <form class="grid" action="atualizar_produto.php" method="post" id="f" enctype="multipart/form-data">
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
                        <div class="f"><label>Preço (R$)</label><input name="preco" type="number" step="0.01" min="0" value="<?= number_format((float) $produto['preco'], 2, '.', '') ?>" required></div>
                        <div class="f"><label>Descrição</label><textarea name="descricao"><?= htmlspecialchars($produto['descricao_produto']) ?></textarea></div>
                    </div>
                    <div class="card">
                        <h2>Variações: cor, tamanho e estoque</h2>
                        <p class="variation-help">Edite cada variação individualmente. Para retirar uma opção de venda, deixe o estoque em zero; os registros são mantidos para preservar pedidos anteriores.</p>
                        <button class="btn btn--o variation-toggle" type="button" id="visualizar-variacoes" aria-expanded="false" aria-controls="painel-variacoes">Visualizar variações</button>
                        <div class="variation-panel" id="painel-variacoes" hidden>
                        <div class="edit-variation-list" id="variacoes-existentes">
                            <?php foreach ($variacoes as $variacao): ?>
                                <?php
                                $idVariacao = (int) $variacao['id_variacao'];
                                $tamanhoAtual = (string) $variacao['tamanho'];
                                $imagemAtual = (string) ($variacao['caminho_imagem'] ?? '');
                                ?>
                                <section class="edit-variation" data-variation>
                                    <h3>Variação #<?= $idVariacao ?> <span class="variation-status" data-variation-status>Somente leitura</span></h3>
                                    <input type="hidden" name="variacoes[<?= $idVariacao ?>][id_variacao]" value="<?= $idVariacao ?>">
                                    <div class="row">
                                        <div class="f"><label for="cor-<?= $idVariacao ?>">Cor</label><input id="cor-<?= $idVariacao ?>" name="variacoes[<?= $idVariacao ?>][cor]" maxlength="55" value="<?= htmlspecialchars($variacao['cor']) ?>" required readonly data-variation-control></div>
                                        <div class="f"><label for="tamanho-<?= $idVariacao ?>">Tamanho</label>
                                            <select id="tamanho-<?= $idVariacao ?>" name="variacoes[<?= $idVariacao ?>][tamanho]" required disabled data-size-select data-variation-control>
                                                <?php foreach (['PP', 'P', 'M', 'G', 'GG', 'XG'] as $tamanho): ?>
                                                    <option value="<?= htmlspecialchars($tamanho) ?>" <?= $tamanhoAtual === $tamanho ? 'selected' : '' ?>><?= htmlspecialchars($tamanho) ?></option>
                                                <?php endforeach; ?>
                                                <?php if (!in_array($tamanhoAtual, ['PP', 'P', 'M', 'G', 'GG', 'XG'], true)): ?>
                                                    <option value="<?= htmlspecialchars($tamanhoAtual) ?>" selected><?= htmlspecialchars($tamanhoAtual) ?></option>
                                                <?php endif; ?>
                                            </select>
                                            <input type="hidden" name="variacoes[<?= $idVariacao ?>][tamanho]" value="<?= htmlspecialchars($tamanhoAtual) ?>" data-size-hidden>
                                        </div>
                                        <div class="f"><label for="estoque-<?= $idVariacao ?>">Quantidade</label><input id="estoque-<?= $idVariacao ?>" name="variacoes[<?= $idVariacao ?>][estoque]" type="number" min="0" value="<?= (int) $variacao['estoque'] ?>" required readonly data-variation-control></div>
                                    </div>
                                    <div class="f">
                                        <label for="imagem-<?= $idVariacao ?>">Foto desta variação (opcional)</label>
                                        <input id="imagem-<?= $idVariacao ?>" name="variacoes[<?= $idVariacao ?>][imagem]" type="file" accept="image/jpeg,image/png,image/webp,image/gif" data-image-input disabled data-variation-control>
                                        <?php if ($imagemAtual !== ''): ?>
                                            <img src="../produtos/<?= htmlspecialchars($imagemAtual) ?>" alt="Foto atual da variação <?= htmlspecialchars($variacao['cor'] . ' ' . $tamanhoAtual) ?>" data-image-preview>
                                        <?php else: ?>
                                            <img alt="Prévia da nova foto" data-image-preview hidden>
                                        <?php endif; ?>
                                    </div>
                                    <div class="actions">
                                        <button class="btn btn--o" type="button" data-edit-variation>Editar variação</button>
                                        <button class="btn btn--d" type="submit" name="excluir_variacao" value="<?= $idVariacao ?>" formnovalidate>Excluir variação</button>
                                    </div>
                                </section>
                            <?php endforeach; ?>
                            <?php if (!$variacoes): ?>
                                <p class="variation-empty">Este produto não possui variações cadastradas.</p>
                            <?php endif; ?>
                        </div>
                        <button class="btn btn--o" type="button" id="adicionar-variacao">Adicionar variação</button>
                        <button class="btn btn--d" type="submit" name="limpar_variacoes_sem_estoque" value="1" formnovalidate>Limpar variações sem estoque</button>
                        </div>
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

    <template id="modelo-nova-variacao">
        <section class="edit-variation" data-variation>
            <h3>Nova variação</h3>
            <div class="row">
                <div class="f"><label data-cor-label>Cor</label><input type="text" data-cor-input maxlength="55" required></div>
                <div class="f"><label data-tamanho-label>Tamanho</label>
                    <select data-tamanho-input required>
                        <option value="">Selecione</option>
                        <option value="PP">PP</option><option value="P">P</option><option value="M">M</option>
                        <option value="G">G</option><option value="GG">GG</option><option value="XG">XG</option>
                    </select>
                </div>
                <div class="f"><label data-estoque-label>Quantidade</label><input type="number" data-estoque-input min="0" value="0" required></div>
            </div>
            <div class="f">
                <label data-imagem-label>Foto da variação (opcional)</label>
                <input type="file" data-imagem-input accept="image/jpeg,image/png,image/webp,image/gif" data-image-input>
                <img alt="Prévia da foto" data-image-preview hidden>
            </div>
            <button type="button" class="btn btn--d" data-remove-variation>Remover nova variação</button>
        </section>
    </template>
    <script>
        (() => {
            const form = document.getElementById('f');
            const list = document.getElementById('variacoes-existentes');
            const template = document.getElementById('modelo-nova-variacao');
            const addButton = document.getElementById('adicionar-variacao');
            const toggleButton = document.getElementById('visualizar-variacoes');
            const panel = document.getElementById('painel-variacoes');
            if (!form || !list || !template || !addButton || !toggleButton || !panel) return;

            let nextIndex = 0;
            toggleButton.addEventListener('click', () => {
                panel.hidden = !panel.hidden;
                toggleButton.setAttribute('aria-expanded', String(!panel.hidden));
                toggleButton.textContent = panel.hidden ? 'Visualizar variações' : 'Ocultar variações';
            });

            function watchImage(card) {
                const input = card.querySelector('[data-image-input]');
                const preview = card.querySelector('[data-image-preview]');
                input.addEventListener('change', () => {
                    if (input.files[0]) {
                        preview.src = URL.createObjectURL(input.files[0]);
                        preview.hidden = false;
                    }
                });
            }

            list.querySelectorAll('[data-variation]').forEach(watchImage);
            list.querySelectorAll('[data-edit-variation]').forEach((button) => {
                const card = button.closest('[data-variation]');
                const color = card.querySelector('[name$="[cor]"]');
                const size = card.querySelector('[data-size-select]');
                const savedSize = card.querySelector('[data-size-hidden]');
                const stock = card.querySelector('[name$="[estoque]"]');
                const image = card.querySelector('[data-image-input]');
                const status = card.querySelector('[data-variation-status]');
                const original = {
                    color: color.value,
                    size: size.value,
                    stock: stock.value
                };

                button.addEventListener('click', () => {
                    const editing = button.getAttribute('aria-pressed') !== 'true';
                    if (!editing) {
                        color.value = original.color;
                        size.value = original.size;
                        stock.value = original.stock;
                        savedSize.value = original.size;
                        image.value = '';
                        const preview = card.querySelector('[data-image-preview]');
                        if (preview.dataset.originalSrc) {
                            preview.src = preview.dataset.originalSrc;
                            preview.hidden = false;
                        } else {
                            preview.removeAttribute('src');
                            preview.hidden = true;
                        }
                    }
                    color.readOnly = !editing;
                    stock.readOnly = !editing;
                    size.disabled = !editing;
                    savedSize.disabled = editing;
                    image.disabled = !editing;
                    button.setAttribute('aria-pressed', String(editing));
                    button.textContent = editing ? 'Cancelar edição' : 'Editar variação';
                    status.textContent = editing ? 'Editando' : 'Somente leitura';
                    card.classList.toggle('is-editing', editing);
                });
            });
            list.querySelectorAll('[data-image-preview]').forEach((preview) => {
                if (!preview.hidden && preview.getAttribute('src')) {
                    preview.dataset.originalSrc = preview.src;
                }
            });
            addButton.addEventListener('click', () => {
                const card = template.content.firstElementChild.cloneNode(true);
                const index = nextIndex++;
                const fields = [
                    ['[data-cor-input]', `novas_variacoes[${index}][cor]`, '[data-cor-label]', `nova-cor-${index}`],
                    ['[data-tamanho-input]', `novas_variacoes[${index}][tamanho]`, '[data-tamanho-label]', `novo-tamanho-${index}`],
                    ['[data-estoque-input]', `novas_variacoes[${index}][estoque]`, '[data-estoque-label]', `novo-estoque-${index}`],
                    ['[data-imagem-input]', `novas_variacoes[${index}][imagem]`, '[data-imagem-label]', `nova-imagem-${index}`]
                ];
                fields.forEach(([selector, name, labelSelector, id]) => {
                    const input = card.querySelector(selector);
                    const label = card.querySelector(labelSelector);
                    input.name = name;
                    input.id = id;
                    label.htmlFor = id;
                });
                card.querySelector('[data-remove-variation]').addEventListener('click', () => card.remove());
                watchImage(card);
                list.appendChild(card);
            });

            form.addEventListener('submit', (event) => {
                const submitter = event.submitter;
                if (submitter && submitter.name === 'excluir_variacao') {
                    if (!window.confirm('Excluir esta variação? Ela será mantida se estiver vinculada a um carrinho ou pedido.')) {
                        event.preventDefault();
                    }
                    return;
                }
                if (submitter && submitter.name === 'limpar_variacoes_sem_estoque') {
                    if (!window.confirm('Excluir deste produto as variações sem estoque que não estejam vinculadas a carrinhos ou pedidos?')) {
                        event.preventDefault();
                    }
                    return;
                }
                const keys = new Set();
                for (const card of list.querySelectorAll('[data-variation]')) {
                    const color = card.querySelector('[name$="[cor]"]').value.trim().toLocaleLowerCase('pt-BR');
                    const size = card.querySelector('[name$="[tamanho]"]').value;
                    if (!color || !size) continue;
                    const key = JSON.stringify([color, size]);
                    if (keys.has(key)) {
                        event.preventDefault();
                        window.alert(`A variação ${color} / ${size} está duplicada.`);
                        return;
                    }
                    keys.add(key);
                }
            });
        })();
    </script>
    <div class="foot">&copy; 2026 Mega Store. Painel administrativo.</div>
</body>
</html>
