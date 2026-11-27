<?php
include("../geral/conexao.php");

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

        <div class="row">
          <div class="f">
            <label for="preco">Preço (R$)</label>
            <input id="preco" type="number" name="preco" step="0.01" min="0" placeholder="0,00" required>
          </div>

          <div class="f">
            <label for="genero">Gênero</label>
            <select id="genero" name="genero" required>
              <option value="">Selecione o gênero</option>
              <?php
              $sql = "SELECT * FROM tb_genero";
              $r = mysqli_query($con, $sql);
              while ($x = mysqli_fetch_assoc($r)) {
                echo "<option value=\"" . $x["id_genero"] . "\">" . htmlspecialchars($x["nome_genero"], ENT_QUOTES, "UTF-8") . "</option>";
              }
              ?>
            </select>
          </div>
        </div>

        <div class="f">
          <label for="categoria">Categoria</label>
          <select id="categoria" name="categoria" required>
            <option value="">Selecione a categoria</option>
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

      <!-- Select de cor -->
      <select
        name="var_cor[]"
        required>

        <option value="">
          Selecione a cor
        </option>
<option value="Azul">
  Azul
</option>

<option value="Azul-claro">
  Azul-claro
</option>

<option value="Azul-marinho">
  Azul-marinho
</option>

<option value="Bege">
  Bege
</option>

<option value="Branco">
  Branco
</option>

<option value="Caqui">
  Caqui
</option>

<option value="Caramelo">
  Caramelo
</option>

<option value="Cinza">
  Cinza
</option>

<option value="Creme">
  Creme
</option>

<option value="Dourado">
  Dourado
</option>

<option value="Jeans">
  Jeans
</option>

<option value="Jeans escuro">
  Jeans escuro
</option>

<option value="Laranja">
  Laranja
</option>

<option value="Lilás">
  Lilás
</option>

<option value="Marrom">
  Marrom
</option>

<option value="Off-white">
  Off-white
</option>

<option value="Prata">
  Prata
</option>

<option value="Preto">
  Preto
</option>

<option value="Rosa">
  Rosa
</option>

<option value="Roxo">
  Roxo
</option>

<option value="Verde">
  Verde
</option>

<option value="Verde-oliva">
  Verde-oliva
</option>

<option value="Vermelho">
  Vermelho
</option>

<option value="Vinho">
  Vinho
</option>

<option value="Amarelo">
  Amarelo
</option>

      </select>

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

        <!-- Checkboxes de tamanhos -->
        <label>
          <input
            type="checkbox"
            name="var_tamanho[__IDX__][]"
            value="PP">
          PP
        </label>

        <label>
          <input
            type="checkbox"
            name="var_tamanho[__IDX__][]"
            value="P">
          P
        </label>

        <label>
          <input
            type="checkbox"
            name="var_tamanho[__IDX__][]"
            value="M">
          M
        </label>

        <label>
          <input
            type="checkbox"
            name="var_tamanho[__IDX__][]"
            value="G">
          G
        </label>

        <label>
          <input
            type="checkbox"
            name="var_tamanho[__IDX__][]"
            value="GG">
          GG
        </label>

        <label>
          <input
            type="checkbox"
            name="var_tamanho[__IDX__][]"
            value="XG">
          XG
        </label>

      </div>
    </div>

  </div>

</template>

<script>

  // Índice usado para identificar cada variação
  let varIndex = 0;
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

      return false;

    }

    cores.push(select.value);

  }

  return true;

}
  // Cria uma nova variação
  function addVariation() {

    const tpl =
      document.getElementById('varTemplate').innerHTML;

    // Substitui o índice do template
    const html =
      tpl.replace(/__IDX__/g, varIndex);

    varIndex++;

    const tmp = document.createElement('div');

    tmp.innerHTML = html;

    const block = tmp.firstElementChild;

    // Botão para remover variação
block
  .querySelector('.removeBtn')
  .addEventListener('click', () => block.remove());


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

  }

  // Remove todas as variações
  function resetVariations() {

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

      const tamanhos =
        b.querySelectorAll(
          'input[type="checkbox"]:checked'
        );

      // Verifica se os campos foram preenchidos
      if (
        cor === '' ||
        !Number.isFinite(stock) ||
        stock <= 0 ||
        tamanhos.length === 0 ||
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
          "Erro: todas as variações devem possuir cor, estoque, imagem e ao menos um tamanho."
        );

      }

    });

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

  // Recebe as variações
  $tamanhos  = $_POST['var_tamanho'] ?? [];
  $cores     = $_POST['var_cor'] ?? [];
  $stocks    = $_POST['var_estoque'] ?? [];

  // Verifica cores repetidas
if(count($cores) != count(array_unique($cores))) {

  $erro .=
    "Não é permitido utilizar a mesma cor em duas variações.<br>";

}
  // Recebe as imagens
  $images = $_FILES['variation_images'] ?? [];

  // Variável de erros
  $erro = '';

  // Validação das variações
  for ($i = 0; $i < count($cores); $i++) {

    $cor = trim($cores[$i]);

    $stock = (int)$stocks[$i];

    $tamanhosSelecionados =
      $tamanhos[$i] ?? [];

    // Verifica tamanhos
    if (count($tamanhosSelecionados) === 0) {

      $erro .=
        "Selecione ao menos um tamanho na variação [$i]<br>";
    }

    // Verifica cor
    if ($cor === '') {

      $erro .=
        "Selecione uma cor na variação [$i]<br>";
    }

    // Verifica estoque
    if ($stock <= 0) {

      $erro .=
        "Digite um estoque válido na variação [$i]<br>";
    }
  }

  // Validações do produto
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
(nome_produto, descricao_produto, preco, id_categoria, id_genero, ativo, data_cadastro)
VALUES
('$produto', '$desc', '$preco', '$categoria', '$genero', '1', '$data')";

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
    $tamanhosSelecionados = $tamanhos[$i] ?? [];

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