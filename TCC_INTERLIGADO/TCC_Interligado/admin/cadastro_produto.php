<?php
require_once __DIR__ . '/../geral/auth.php';
require_admin();

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
            <a href="../home/Tl_principal.php" class="logo"><b>MEGA</b><span>STORE</span></a>
            <ul class="nav">
                <li><a href="cadastro_produto.php" class="on">Novo Produto</a></li>
                <li><a href="editar_produto.php">Editar Produto</a></li>
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
                    <h2>Variações do produto</h2>
                    <div class="f">
                        <label for="quantidade-cores">Quantidade de cores</label>
                        <div class="variation-count">
                            <input id="quantidade-cores" name="quantidade_cores" type="number" min="1" max="50" value="1" required>
                            <button class="btn btn--o" type="button" id="gerar-variacoes">Criar cards</button>
                        </div>
                        <small class="variation-help">Informe quantas cores o produto terá. Depois, configure cada cor, seus tamanhos e uma foto opcional.</small>
                    </div>
                    <div class="variation-list" id="lista-variacoes" aria-live="polite"></div>
                </div>
            </div>

            <aside>
                <div class="card">
                    <h2>Imagem padrão (opcional)</h2>
                    <p class="variation-help">Usada nas cores que não tiverem uma foto própria.</p>
                    <label class="drop" id="drop"><span data-drop-label>Clique para enviar<br>uma imagem</span><input type="file" name="imagem" accept="image/jpeg,image/png,image/webp,image/gif" hidden id="img"></label>
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

    <template id="modelo-variacao">
        <section class="variation-card">
            <h3>Cor <span data-numero-variacao></span></h3>
            <div class="f">
                <span class="variation-label">Selecione uma cor</span>
                <div class="color-options">
                    <button type="button" class="color-option" data-color-option="Preto" aria-pressed="false"><span class="color-swatch" style="--swatch-color:#111827"></span>Preto</button>
                    <button type="button" class="color-option" data-color-option="Branco" aria-pressed="false"><span class="color-swatch" style="--swatch-color:#ffffff"></span>Branco</button>
                    <button type="button" class="color-option" data-color-option="Azul" aria-pressed="false"><span class="color-swatch" style="--swatch-color:#2563eb"></span>Azul</button>
                    <button type="button" class="color-option" data-color-option="Vermelho" aria-pressed="false"><span class="color-swatch" style="--swatch-color:#dc2626"></span>Vermelho</button>
                    <button type="button" class="color-option" data-color-option="Verde" aria-pressed="false"><span class="color-swatch" style="--swatch-color:#16a34a"></span>Verde</button>
                    <button type="button" class="color-option" data-color-option="Amarelo" aria-pressed="false"><span class="color-swatch" style="--swatch-color:#eab308"></span>Amarelo</button>
                    <button type="button" class="color-option" data-color-option="Rosa" aria-pressed="false"><span class="color-swatch" style="--swatch-color:#ec4899"></span>Rosa</button>
                    <button type="button" class="color-option" data-color-option="Roxo" aria-pressed="false"><span class="color-swatch" style="--swatch-color:#7e22ce"></span>Roxo</button>
                    <button type="button" class="color-option" data-color-option="Cinza" aria-pressed="false"><span class="color-swatch" style="--swatch-color:#9ca3af"></span>Cinza</button>
                    <button type="button" class="color-option" data-color-option="Marrom" aria-pressed="false"><span class="color-swatch" style="--swatch-color:#7c4a2d"></span>Marrom</button>
                    <button type="button" class="color-option" data-color-option="Bege" aria-pressed="false"><span class="color-swatch" style="--swatch-color:#d6c1a1"></span>Bege</button>
                    <button type="button" class="color-option color-option--add" data-add-color>+ Adicionar cor</button>
                </div>
                <div class="custom-color-field" data-custom-color hidden>
                    <label data-custom-color-label>Nome da nova cor</label>
                    <div class="custom-color-entry">
                        <input type="text" data-custom-color-input maxlength="55" placeholder="Ex.: Azul-marinho">
                        <button class="btn btn--o" type="button" data-confirm-color>Adicionar</button>
                    </div>
                </div>
                <input type="hidden" data-input-cor>
            </div>
            <div class="f">
                <span class="variation-label">Tamanho e quantidade em estoque</span>
                <div class="size-inventory-list">
                    <div class="size-inventory"><label class="chip"><input type="checkbox" data-tamanho value="PP"><span>PP</span></label><label class="inventory-count">Quantidade<input type="number" data-estoque min="0" value="0" disabled></label></div>
                    <div class="size-inventory"><label class="chip"><input type="checkbox" data-tamanho value="P"><span>P</span></label><label class="inventory-count">Quantidade<input type="number" data-estoque min="0" value="0" disabled></label></div>
                    <div class="size-inventory"><label class="chip"><input type="checkbox" data-tamanho value="M"><span>M</span></label><label class="inventory-count">Quantidade<input type="number" data-estoque min="0" value="0" disabled></label></div>
                    <div class="size-inventory"><label class="chip"><input type="checkbox" data-tamanho value="G"><span>G</span></label><label class="inventory-count">Quantidade<input type="number" data-estoque min="0" value="0" disabled></label></div>
                    <div class="size-inventory"><label class="chip"><input type="checkbox" data-tamanho value="GG"><span>GG</span></label><label class="inventory-count">Quantidade<input type="number" data-estoque min="0" value="0" disabled></label></div>
                    <div class="size-inventory"><label class="chip"><input type="checkbox" data-tamanho value="XG"><span>XG</span></label><label class="inventory-count">Quantidade<input type="number" data-estoque min="0" value="0" disabled></label></div>
                </div>
            </div>
            <div class="f">
                <label data-label-imagem>Foto da cor (opcional)</label>
                <input type="file" data-input-imagem accept="image/jpeg,image/png,image/webp,image/gif">
                <img class="variation-preview" data-preview alt="Prévia da foto da variação" hidden>
            </div>
        </section>
    </template>

    <div class="foot">&copy; 2026 Mega Store. Painel administrativo.</div>
    <script>
        (() => {
            const quantidade = document.getElementById('quantidade-cores');
            const botaoGerar = document.getElementById('gerar-variacoes');
            const lista = document.getElementById('lista-variacoes');
            const modelo = document.getElementById('modelo-variacao');
            const formulario = document.getElementById('f');
            const imagemPadrao = document.getElementById('img');
            const drop = document.getElementById('drop');
            const dropLabel = drop.querySelector('[data-drop-label]');

            function gerarVariacoes() {
                const total = Number(quantidade.value);
                if (!Number.isInteger(total) || total < 1 || total > 50) {
                    quantidade.setCustomValidity('Informe uma quantidade entre 1 e 50.');
                    quantidade.reportValidity();
                    quantidade.setCustomValidity('');
                    return;
                }

                if (lista.children.length > 0 && !window.confirm('Gerar novamente os cards apagará os dados já preenchidos. Deseja continuar?')) {
                    return;
                }

                lista.replaceChildren();
                for (let indice = 0; indice < total; indice += 1) {
                    const card = modelo.content.firstElementChild.cloneNode(true);
                    const nomeCor = card.querySelector('[data-input-cor]');
                    const labelImagem = card.querySelector('[data-label-imagem]');
                    const numero = indice + 1;

                    card.querySelector('[data-numero-variacao]').textContent = numero;
                    nomeCor.name = `variacoes[${indice}][cor]`;
                    labelImagem.htmlFor = `imagem-variacao-${indice}`;
                    const customColorField = card.querySelector('[data-custom-color]');
                    const customColorInput = card.querySelector('[data-custom-color-input]');
                    const customColorLabel = card.querySelector('[data-custom-color-label]');
                    customColorInput.id = `cor-personalizada-${indice}`;
                    customColorLabel.htmlFor = customColorInput.id;
                    const colorButtons = card.querySelector('.color-options');
                    const colorOptions = () => card.querySelectorAll('[data-color-option]');
                    const selecionarCor = (botao) => {
                        nomeCor.value = botao.dataset.colorOption;
                        colorOptions().forEach((opcao) => {
                            opcao.setAttribute('aria-pressed', String(opcao === botao));
                        });
                        customColorField.hidden = true;
                        customColorInput.value = '';
                    };
                    colorOptions().forEach((botao) => {
                        botao.addEventListener('click', () => selecionarCor(botao));
                    });
                    card.querySelector('[data-add-color]').addEventListener('click', () => {
                        customColorField.hidden = false;
                        customColorInput.focus();
                    });
                    const adicionarCor = () => {
                        const cor = customColorInput.value.trim();
                        if (!cor) {
                            customColorInput.setCustomValidity('Digite o nome da cor.');
                            customColorInput.reportValidity();
                            customColorInput.setCustomValidity('');
                            return;
                        }
                        const existente = Array.from(colorOptions()).find((opcao) =>
                            opcao.dataset.colorOption.toLocaleLowerCase('pt-BR') === cor.toLocaleLowerCase('pt-BR')
                        );
                        if (existente) {
                            selecionarCor(existente);
                            return;
                        }
                        const botaoCor = document.createElement('button');
                        botaoCor.type = 'button';
                        botaoCor.className = 'color-option';
                        botaoCor.dataset.colorOption = cor;
                        botaoCor.setAttribute('aria-pressed', 'false');
                        const amostra = document.createElement('span');
                        amostra.className = 'color-swatch color-swatch--custom';
                        botaoCor.append(amostra, document.createTextNode(cor));
                        botaoCor.addEventListener('click', () => selecionarCor(botaoCor));
                        colorButtons.insertBefore(botaoCor, card.querySelector('[data-add-color]'));
                        selecionarCor(botaoCor);
                    };
                    card.querySelector('[data-confirm-color]').addEventListener('click', adicionarCor);
                    customColorInput.addEventListener('keydown', (evento) => {
                        if (evento.key === 'Enter') {
                            evento.preventDefault();
                            adicionarCor();
                        }
                    });
                    card.querySelectorAll('[data-tamanho]').forEach((tamanho) => {
                        const estoque = tamanho.closest('.size-inventory').querySelector('[data-estoque]');
                        const atualizarEstoque = () => {
                            tamanho.name = `variacoes[${indice}][tamanhos][]`;
                            estoque.name = `variacoes[${indice}][estoque][${tamanho.value}]`;
                            estoque.disabled = !tamanho.checked;
                            estoque.required = tamanho.checked;
                        };
                        tamanho.addEventListener('change', atualizarEstoque);
                        atualizarEstoque();
                    });
                    const inputImagem = card.querySelector('[data-input-imagem]');
                    inputImagem.name = `variacoes[${indice}][imagem]`;
                    inputImagem.id = `imagem-variacao-${indice}`;
                    inputImagem.addEventListener('change', () => {
                        const arquivo = inputImagem.files[0];
                        const previa = card.querySelector('[data-preview]');
                        if (arquivo) {
                            previa.src = URL.createObjectURL(arquivo);
                            previa.hidden = false;
                        } else {
                            previa.removeAttribute('src');
                            previa.hidden = true;
                        }
                    });
                    lista.appendChild(card);
                }
            }

            botaoGerar.addEventListener('click', gerarVariacoes);
            imagemPadrao.addEventListener('change', () => {
                const arquivo = imagemPadrao.files[0];
                if (arquivo) {
                    drop.style.backgroundImage = `url("${URL.createObjectURL(arquivo)}")`;
                    dropLabel.textContent = '';
                } else {
                    drop.style.backgroundImage = '';
                    dropLabel.textContent = 'Clique para enviar uma imagem';
                }
            });

            formulario.addEventListener('submit', (evento) => {
                const cards = Array.from(lista.children);
                if (cards.length !== Number(quantidade.value) || cards.length === 0) {
                    evento.preventDefault();
                    window.alert('Clique em “Criar cards” para gerar as variações do produto.');
                    return;
                }

                const cores = new Set();
                for (const [indice, card] of cards.entries()) {
                    const cor = card.querySelector('[data-input-cor]').value.trim().toLocaleLowerCase('pt-BR');
                    const tamanhos = card.querySelectorAll('[data-tamanho]:checked');
                    if (cor === '' || tamanhos.length === 0) {
                        evento.preventDefault();
                        window.alert(`Preencha a cor e selecione ao menos um tamanho no card ${indice + 1}.`);
                        return;
                    }
                    if (cores.has(cor)) {
                        evento.preventDefault();
                        window.alert(`A cor "${card.querySelector('[data-input-cor]').value.trim()}" está repetida.`);
                        return;
                    }
                    cores.add(cor);
                }
            });
        })();
    </script>
</body>
</html>
