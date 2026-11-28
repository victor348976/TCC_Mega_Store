<?php
include("../geral/conexao.php");
$erro      = '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastrar produto | Mega Store</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../admin/admin.css">
  <link rel="stylesheet" href="Cad_produto3.css">
</head>

<body>
  <header class="header">
    <div class="header__inner">
      <a href="../home/Tl_principal.php" class="logo"><b>MEGA</b><span>STORE</span></a>
      <ul class="nav">
        <li><a href="../mega-store/index.php">Coleções</a></li>
        <li><a href="../home/Tl_principal.php">Página inicial</a></li>
        <li><a href="../admin/cadastro_produto.php">Painel administrativo</a></li>
      </ul>
      <span class="user">MEGA STORE</span>
    </div>
  </header>

  <main class="page product-page">
    <span class="label">Catálogo · Administração</span>
    <h1>Cadastrar produto</h1>
    <p class="sub">Adicione uma nova peça à coleção Mega Store.</p>
    <div class="divider"></div>

    <form id="productForm" method="POST" action="Cad_produto3.php" enctype="multipart/form-data">
      <section class="card product-card">
        <span class="product-section-label">01 / Informações</span>
        <h2>Detalhes do produto</h2>

        <div class="f">
          <label for="produto">Nome do produto</label>
          <input id="produto" type="text" name="produto" placeholder="Ex.: Camisa de linho clássica" required>
        </div>

        <div class="f">
          <label for="desc">Descrição</label>
          <textarea id="desc" name="desc" placeholder="Descreva os detalhes, materiais e características do produto." required></textarea>
        </div>
        <div class="f">
    <label for="buscarSelo">Selo do produto</label>

    <div class="autocomplete">
        <input
            type="text"
            id="buscarSelo"
            placeholder="Digite ou selecione um selo..."
            autocomplete="off"
        >

        <div class="autocomplete-list"></div>
    </div>

    <select id="selo" name="selo" style="display:none;">
    <option value="Nenhum">Nenhum</option>
    <option value="Novo">Novo</option>
    <option value="Exclusivo">Exclusivo</option>
</select>
</div>
        <div class="row">
          <div class="f">
            <label for="preco">Preço (R$)</label>
            <input id="preco" type="number" name="preco" step="0.01" min="0" placeholder="0,00" required>
          </div>

          <div class="f">
    <label for="buscarGenero">Gênero</label>

    <div class="autocomplete">
        <input
            type="text"
            id="buscarGenero"
            placeholder="Digite ou selecione um gênero..."
            autocomplete="off"
            required
        >

        <div class="autocomplete-list"></div>
    </div>

    <select id="genero" name="genero" style="display:none;">
        <option value="">Selecione o gênero</option>
        <?php
        $sql = "SELECT * FROM tb_genero";
        $r = mysqli_query($con, $sql);

        while ($x = mysqli_fetch_assoc($r)) {
            echo "<option value=\"" . $x["id_genero"] . "\">" .
                htmlspecialchars($x["nome_genero"], ENT_QUOTES, "UTF-8") .
                "</option>";
        }
        ?>
    </select>
</div>

        <div class="f">
    <label for="buscarCategoria">Categoria</label>

    <div class="autocomplete">
        <input
            type="text"
            id="buscarCategoria"
            placeholder="Digite ou selecione uma categoria..."
            autocomplete="off"
            required
        >

        <div class="autocomplete-list"></div>
    </div>

    <select id="categoria" name="categoria" style="display:none;">
        <option value="">Selecione</option>
        <?php
        $sql = "SELECT * FROM tb_categoria";
        $r = mysqli_query($con, $sql);

        while ($x = mysqli_fetch_assoc($r)) {
            echo "<option value=\"" . $x["id_categoria"] . "\">" . htmlspecialchars($x["nome_categoria"], ENT_QUOTES, "UTF-8") . "</option>";
        }
        ?>
    </select>
</div>
      </section>

      <section class="card product-card product-variations">
        <span class="product-section-label">02 / Opções</span>
        <h2>Variações do produto</h2>
        <p class="variation-help">Configure cada cor, estoque, imagem e ao menos um tamanho.</p>

        <div id="variationsContainer" class="variation-list"></div>

        <div class="variation-toolbar">
          <div class="variation-count">
            <label class="variation-label" for="generateCount">Quantidade</label>
            <input id="generateCount" type="number" min="1" placeholder="Nº">
          </div>
          <button class="btn btn--o" type="button" id="btnGenerate">Gerar variações</button>
          <button class="btn btn--o" type="button" id="btnAdd">Adicionar variação</button>
          <button class="btn btn--d" type="button" id="btnReset">Excluir todas</button>
        </div>
      </section>

      <div class="actions product-actions">
        <button class="btn" type="submit" name="cadastrar" value="Cadastrar">Cadastrar produto</button>
        <button class="btn btn--o" type="reset">Limpar formulário</button>
      </div>
    </form>
  </main>
</body>

<!-- Template usado para criar novas variações -->
<template id="varTemplate">

  <div
    class="var-block"
  >

    <div class="variation-fields">

      <div class="color-field">
    <div class="autocomplete">
        <input
            type="text"
            class="buscarCor"
            placeholder="Digite ou selecione uma cor..."
            autocomplete="off"
            required
        >

        <div class="autocomplete-list"></div>
    </div>

    <select name="var_cor[]" style="display:none;">
        <option value="">Selecione a cor</option>
        <option value="Azul">Azul</option>
        <option value="Azul-claro">Azul-claro</option>
        <option value="Azul-marinho">Azul-marinho</option>
        <option value="Bege">Bege</option>
        <option value="Branco">Branco</option>
        <option value="Caqui">Caqui</option>
        <option value="Caramelo">Caramelo</option>
        <option value="Cinza">Cinza</option>
        <option value="Creme">Creme</option>
        <option value="Dourado">Dourado</option>
        <option value="Jeans">Jeans</option>
        <option value="Jeans escuro">Jeans escuro</option>
        <option value="Laranja">Laranja</option>
        <option value="Lilás">Lilás</option>
        <option value="Marrom">Marrom</option>
        <option value="Off-white">Off-white</option>
        <option value="Prata">Prata</option>
        <option value="Preto">Preto</option>
        <option value="Rosa">Rosa</option>
        <option value="Roxo">Roxo</option>
        <option value="Verde">Verde</option>
        <option value="Verde-oliva">Verde-oliva</option>
        <option value="Vermelho">Vermelho</option>
        <option value="Vinho">Vinho</option>
        <option value="Amarelo">Amarelo</option>
    </select>
</div>
      <!-- Campo estoque -->
      <input
        name="var_estoque[]"
        type="number"
        placeholder="Estoque"
        min="0"
        required>

      <!-- Upload de imagens -->
      <input
        name="variation_images[__IDX__][]"
        type="file"
        multiple
        accept="image/*"
        required>
        <div class="preview-imagens"></div>
      <!-- Remove a variação -->
      <button
        type="button"
        class="removeBtn"
        aria-label="Remover variação">
        X
      </button>

    </div>

    <!-- Área dos tamanhos -->
    <div class="variation-size-area">
    <strong>Tamanhos:</strong>

    <div class="size-options">
        <label>
            <input type="checkbox" name="var_tamanho[__IDX__][]" value="PP">
            PP
        </label>
        <label>
            <input type="checkbox" name="var_tamanho[__IDX__][]" value="P">
            P
        </label>
        <label>
            <input type="checkbox" name="var_tamanho[__IDX__][]" value="M">
            M
        </label>
        <label>
            <input type="checkbox" name="var_tamanho[__IDX__][]" value="G">
            G
        </label>
        <label>
            <input type="checkbox" name="var_tamanho[__IDX__][]" value="GG">
            GG
        </label>
        <label>
            <input type="checkbox" name="var_tamanho[__IDX__][]" value="XG">
            XG
        </label>
    </div>

    <div class="size-numeric" style="display:none;">
        <label>Digite os tamanhos separados por vírgula</label>
        <input type="text" name="var_tamanho_num[__IDX__]" placeholder="Ex.: 2, 4, 6, 8">
        <small class="size-limit"></small>
    </div>
</div>

  </div>

</template>

<script>
  
  // Índice usado para identificar cada variação
  let varIndex = 0;

  function configurarAutocomplete(input, select) {
    const lista = input.parentElement.querySelector('.autocomplete-list');

    // Move a lista para fora do grid
    document.body.appendChild(lista);

    // Guarda a referência para permitir removê-la depois
    input._autocompleteList = lista;

    function normalizar(texto) {
        return texto
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }

    // Posiciona a lista em relação ao campo
    function posicionarLista() {
        if (!lista.classList.contains('visivel')) return;

        const campo = input.getBoundingClientRect();

        lista.style.left = campo.left + 'px';
        lista.style.width = campo.width + 'px';

        const altura = Math.min(lista.scrollHeight, 160);
        const espacoAbaixo = window.innerHeight - campo.bottom;
        const espacoAcima = campo.top;

        if (espacoAbaixo < altura + 10 && espacoAcima > espacoAbaixo) {
            lista.style.top = (campo.top - altura - 4) + 'px';
        } else {
            lista.style.top = (campo.bottom + 4) + 'px';
        }
    }

    function atualizarLista() {
        const termo = normalizar(input.value.trim());

        lista.innerHTML = '';

        const opcoes = Array.from(select.options).filter(opcao => {
            if (opcao.value === '') return false;

            return normalizar(opcao.text).startsWith(termo);
        });

        if (opcoes.length === 0) {
            lista.classList.remove('visivel');
            select.value = '';
            return;
        }

        opcoes.forEach(opcao => {
            const item = document.createElement('div');

            item.className = 'autocomplete-item';
            item.textContent = opcao.text;

            item.addEventListener('mousedown', function(e) {
                e.preventDefault();

                input.value = opcao.text;
                select.value = opcao.value;

                lista.classList.remove('visivel');
                lista.innerHTML = '';

                input.blur();

                if (select.id === 'categoria' || select.id === 'genero') {
                  atualizarTamanhos();
                } else if (select.name === 'var_cor[]') {
                  validarCores();
                }
            });

            lista.appendChild(item);
        });

        lista.classList.add('visivel');
        posicionarLista();
    }

    input.addEventListener('input', function() {
        const nome = normalizar(this.value.trim());

        const opcao = Array.from(select.options).find(
            o => normalizar(o.text) === nome
        );

        select.value = opcao ? opcao.value : '';

        atualizarLista();
    });

    input.addEventListener('focus', atualizarLista);

    input.addEventListener('blur', function() {
        setTimeout(() => {
            lista.classList.remove('visivel');
        }, 150);
    });

    // Mantém a lista alinhada ao campo durante a rolagem
    window.addEventListener('scroll', posicionarLista, true);
    window.addEventListener('resize', posicionarLista);
}
// Verifica se existem cores repetidas
function validarCores() {

  const selects = document.querySelectorAll(
    'select[name="var_cor[]"]'
  );

  const cores = [];

  for (const select of selects) {

    if (select.value == '') {
      continue;
    }

    if (cores.includes(select.value)) {

      alert(
        "Essa cor já foi selecionada em outra variação."
      );

      select.value = "";

      const campoCor = select.closest('.var-block').querySelector('.buscarCor');
      campoCor.value = '';

      return false;

    }

    cores.push(select.value);

  }

  return true;

}
  function tipoTamanho() {
    const genero = document.getElementById('genero');
    const categoria = document.getElementById('categoria');

    const nomeGenero = genero.options[genero.selectedIndex]?.text.toLowerCase() || '';
    const nomeCategoria = categoria.options[categoria.selectedIndex]?.text.toLowerCase() || '';

    if (nomeGenero.includes('infantil')) {
        return 'infantil';
    }

    if (
        nomeCategoria.includes('calçado') ||
        nomeCategoria.includes('calcado') ||
        nomeCategoria.includes('sapato')
    ) {
        return 'calcado';
    }

    return 'normal';
}

  function atualizarTamanhos() {
    const tipo = tipoTamanho();

    document.querySelectorAll('.var-block').forEach(block => {
        const normal = block.querySelector('.size-options');
        const numerico = block.querySelector('.size-numeric');
        const campo = numerico.querySelector('input');
        const limite = numerico.querySelector('.size-limit');

        if (tipo === 'normal') {
            normal.style.display = '';
            numerico.style.display = 'none';
            campo.required = false;
        } else {
            normal.style.display = 'none';
            numerico.style.display = 'block';
            campo.required = true;

            if (tipo === 'infantil') {
                campo.placeholder = 'Ex.: 2, 4, 6, 8';
                limite.textContent = 'Tamanhos numéricos de 1 a 20.';
            } else {
                campo.placeholder = 'Ex.: 35, 36, 37, 38';
                limite.textContent = 'Tamanhos numéricos de 1 a 50.';
            }
        }
    });
}
  // Cria uma nova variação
  function addVariation() {
    const buscarSelo = document.getElementById('buscarSelo');

buscarSelo.addEventListener('focus', function() {
    if (this.value === 'Nenhum') {
        this.value = '';
    }
});
    const tpl =
      document.getElementById('varTemplate').innerHTML;

    // Substitui o índice do template
    const html =
      tpl.replace(/__IDX__/g, varIndex);

    varIndex++;

    const tmp = document.createElement('div');

    tmp.innerHTML = html;

    const block = tmp.firstElementChild;
    const inputImagens = block.querySelector('input[type="file"]');
    const preview = block.querySelector('.preview-imagens');

    inputImagens.addEventListener('change', function() {
    preview.innerHTML = '';

    Array.from(this.files).forEach(arquivo => {
        if (!arquivo.type.startsWith('image/')) return;

        const img = document.createElement('img');
        img.src = URL.createObjectURL(arquivo);
        img.classList.add('preview-img');

        preview.appendChild(img);
    });
});
    const buscaCor = block.querySelector('.buscarCor');
    const selectCor = block.querySelector('select[name="var_cor[]"]');

    configurarAutocomplete(buscaCor, selectCor);
    // Botão para remover variação
block
  .querySelector('.removeBtn')
  .addEventListener('click', () => {
      buscaCor._autocompleteList.remove();
      block.remove();
  });


// Verifica se a cor já foi usada
block
  .querySelector('select[name="var_cor[]"]')
  .addEventListener(
    'change',
    validarCores
  );
    // Adiciona a variação na tela
    document
      .getElementById('variationsContainer')
      .appendChild(block);
    atualizarTamanhos();
  }
  document.getElementById('categoria').addEventListener('change', atualizarTamanhos);
document.getElementById('genero').addEventListener('change', atualizarTamanhos);
  // Remove todas as variações
  function resetVariations() {
  document.querySelectorAll('#variationsContainer .buscarCor').forEach(input => {
    input._autocompleteList.remove();
});
    document.getElementById(
      'variationsContainer'
    ).innerHTML = '';

    varIndex = 0;

  }

  // Gera várias variações automaticamente
  function generateVariations(n) {

    for (let i = 0; i < n; i++) {
      addVariation();
    }

  }

  // Valida todas as variações
  function allVariationsValid() {

    const blocks =
      document.querySelectorAll(
        '#variationsContainer .var-block'
      );

    // Verifica se existe ao menos uma variação
    if (blocks.length === 0) {
      return false;
    }

    // Percorre todas as variações
    for (const b of blocks) {

      const cor =
        b.querySelector(
          'select[name="var_cor[]"]'
        ).value;

      const stock = parseInt(
        b.querySelector(
          'input[name="var_estoque[]"]'
        ).value || '0',
        10
      );

      const files =
        b.querySelector(
          'input[type="file"]'
        ).files.length;

      let tamanhosValidos = false;

      if (tipoTamanho() === 'normal') {
          tamanhosValidos = b.querySelectorAll('input[type="checkbox"]:checked').length > 0;
      } else {
      const campo = b.querySelector('.size-numeric input');
      const numeros = campo.value.split(',').map(n => n.trim()).filter(n => n !== '');
      const limite = tipoTamanho() === 'infantil' ? 20 : 50;

    tamanhosValidos = numeros.length > 0 &&
        numeros.every(n => /^\d+$/.test(n) && Number(n) >= 1 && Number(n) <= limite);
}

      // Verifica se os campos foram preenchidos
      if (
        cor === '' ||
        !Number.isFinite(stock) ||
        stock <= 0 ||
        !tamanhosValidos ||
        files === 0
      ) {
        return false;
      }

    }

    return true;

  }

  // Evento botão adicionar
  document
    .getElementById('btnAdd')
    .addEventListener(
      'click',
      () => addVariation()
    );

  // Evento botão resetar
  document
    .getElementById('btnReset')
    .addEventListener(
      'click',
      () => resetVariations()
    );

  // Evento botão gerar
  document
    .getElementById('btnGenerate')
    .addEventListener('click', () => {

      const n = parseInt(
        document.getElementById(
          'generateCount'
        ).value || '0',
        10
      );

      if (n > 0) {
        generateVariations(n);
      }

    });

  // Validação antes de enviar o formulário
  document
    .getElementById('productForm')
    .addEventListener('submit', function(e) {

      if (!allVariationsValid()) {

        e.preventDefault();

        alert(
          "Erro: todas as variações devem possuir cor, estoque, imagem e tamanhos válidos."
        );

      }

    });
  const buscaCategoria = document.getElementById('buscarCategoria');
  const selectCategoria = document.getElementById('categoria');

  configurarAutocomplete(buscaCategoria, selectCategoria);

  const buscaGenero = document.getElementById('buscarGenero');
  const selectGenero = document.getElementById('genero');

  configurarAutocomplete(buscaGenero, selectGenero);

  const buscaSelo = document.getElementById('buscarSelo');
  const selectSelo = document.getElementById('selo');

  configurarAutocomplete(buscaSelo, selectSelo);
  // Cria uma variação inicial automaticamente
  addVariation();

  
</script>

<?php

// Pega a data atual
$data = date('Y/m/d');

// Verifica se clicou no botão cadastrar
if (isset($_POST["cadastrar"])) {

  // Recebe os dados do formulário
$produto   = $_POST["produto"] ?? '';
$desc      = $_POST["desc"] ?? '';
$preco     = $_POST["preco"] ?? '';
$genero    = $_POST["genero"] ?? '';
$categoria = $_POST["categoria"] ?? '';
$selo      = $_POST["selo"] ?? '';

$tamanhos      = $_POST['var_tamanho'] ?? [];
$tamanhosNum   = $_POST['var_tamanho_num'] ?? [];
$cores         = $_POST['var_cor'] ?? [];
$stocks        = $_POST['var_estoque'] ?? [];
$images        = $_FILES['variation_images'] ?? [];

$erro = '';

// Busca o nome do gênero
$sqlGenero = "SELECT nome_genero FROM tb_genero WHERE id_genero = '$genero'";
$resultGenero = mysqli_query($con, $sqlGenero);
$nomeGenero = strtolower(mysqli_fetch_assoc($resultGenero)['nome_genero'] ?? '');

// Busca o nome da categoria
$sqlCategoria = "SELECT nome_categoria FROM tb_categoria WHERE id_categoria = '$categoria'";
$resultCategoria = mysqli_query($con, $sqlCategoria);
$nomeCategoria = strtolower(mysqli_fetch_assoc($resultCategoria)['nome_categoria'] ?? '');

if (strpos($nomeGenero, 'infantil') !== false) {
    $tipoTamanhoPHP = 'infantil';
} elseif (
    strpos($nomeCategoria, 'calçado') !== false ||
    strpos($nomeCategoria, 'calcado') !== false ||
    strpos($nomeCategoria, 'sapato') !== false
) {
    $tipoTamanhoPHP = 'calcado';
} else {
    $tipoTamanhoPHP = 'normal';
}

// Verifica cores repetidas
if (count($cores) != count(array_unique($cores))) {
    $erro .= "Não é permitido utilizar a mesma cor em duas variações.<br>";
}

  // Validação das variações
for ($i = 0; $i < count($cores); $i++) {

    $cor = trim($cores[$i]);
    $stock = (int)($stocks[$i] ?? 0);

    if ($tipoTamanhoPHP === 'normal') {
        $tamanhosSelecionados = $tamanhos[$i] ?? [];
    } else {
        $textoTamanhos = $tamanhosNum[$i] ?? '';
        $tamanhosSelecionados = array_map('trim', explode(',', $textoTamanhos));
        $tamanhosSelecionados = array_filter($tamanhosSelecionados, fn($t) => $t !== '');

        $limite = $tipoTamanhoPHP === 'infantil' ? 20 : 50;

        foreach ($tamanhosSelecionados as $tamanhoNum) {
            if (!ctype_digit($tamanhoNum) || (int)$tamanhoNum < 1 || (int)$tamanhoNum > $limite) {
                $erro .= "Tamanho inválido na variação [$i].<br>";
                break;
            }
        }

        $tamanhosSelecionados = array_unique($tamanhosSelecionados);
    }

    if (count($tamanhosSelecionados) === 0) {
        $erro .= "Selecione ou digite ao menos um tamanho na variação [$i].<br>";
    }

    if ($cor === '') {
        $erro .= "Selecione uma cor na variação [$i].<br>";
    }

    if ($stock <= 0) {
        $erro .= "Digite um estoque válido na variação [$i].<br>";
    }
}
  if ($produto == '') {
    $erro .= "Digite o nome do produto<br>";
  }

  if ($desc == '') {
    $erro .= "Digite a descrição<br>";
  }

  if ($preco == '') {
    $erro .= "Digite um preço<br>";
  }

  if ($genero == '') {
    $erro .= "Selecione o gênero<br>";
  }

  if ($categoria == '') {
    $erro .= "Selecione a categoria<br>";
  }

  // Se não houver erros
 if ($erro == '') {

    // Insere o produto no banco
$sql = "INSERT INTO tb_produto
(nome_produto, descricao_produto, preco, id_categoria, id_genero, ativo, data_cadastro, selo)
VALUES
('$produto', '$desc', '$preco', '$categoria', '$genero', '1', '$data', '$selo')";

mysqli_query($con, $sql);

$id_produto = mysqli_insert_id($con);

// Pasta de imagens
$pasta = "../produtos/uploads/";

if (!is_dir($pasta)) {
    mkdir($pasta, 0777, true);
}

// Percorre as variações
for ($i = 0; $i < count($cores); $i++) {

    $cor = trim($cores[$i]);
    $stock = (int)$stocks[$i];
    if ($tipoTamanhoPHP === 'normal') {
    $tamanhosSelecionados = $tamanhos[$i] ?? [];
} else {
    $tamanhosSelecionados = array_map('trim', explode(',', $tamanhosNum[$i] ?? ''));
    $tamanhosSelecionados = array_filter($tamanhosSelecionados, fn($t) => $t !== '');
    $tamanhosSelecionados = array_unique($tamanhosSelecionados);
}

    // Armazena os nomes das imagens dessa cor
    $imagensCor = [];

    // Envia cada imagem apenas uma vez
    if (!empty($images['name'][$i])) {

        foreach ($images['name'][$i] as $idx => $filename) {

            if ($filename == '') {
                continue;
            }

            $tmp_name = $images['tmp_name'][$i][$idx];

            $extensao = pathinfo($filename, PATHINFO_EXTENSION);

            // Gera um único nome para essa imagem
            $novoNome = uniqid() . "." . $extensao;

            $destino = $pasta . $novoNome;

            if (move_uploaded_file($tmp_name, $destino)) {
                $imagensCor[] = $novoNome;
            }
        }
    }

    // Cria uma variação para cada tamanho
    foreach ($tamanhosSelecionados as $tamanho) {

        $tamanho = trim($tamanho);

        $sql = "INSERT INTO tb_variacao_produto
                (id_produto, tamanho, cor, estoque)
                VALUES
                ('$id_produto', '$tamanho', '$cor', $stock)";

        mysqli_query($con, $sql);

        $id_variacao = mysqli_insert_id($con);

        // Vincula as mesmas imagens à variação de cada tamanho
        foreach ($imagensCor as $nomeImagem) {

            $sqlImagem = "INSERT INTO tb_imagem_produto
                          (id_variacao, caminho_imagem)
                          VALUES
                          ('$id_variacao', '$nomeImagem')";

            mysqli_query($con, $sqlImagem);
        }
    }
}
    }
    // Mensagem de sucesso
    echo '<div class="alert-box product-result">Produto cadastrado com sucesso.</div>';

  } 
  else {

    echo '<div class="product-error">' . $erro . '</div>';

  }

?>

</html>