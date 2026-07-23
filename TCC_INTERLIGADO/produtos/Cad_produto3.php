<?php
// Faz a conexão com o banco de dados
include("../geral/conexao.php");

// Importa o cabeçalho padrão do sistema
include("../geral/head.php");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <!-- Configurações básicas da página -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produto</title>
</head>

<body>

  <center>

    <!-- Formulário principal de cadastro -->
    <form
      id="productForm"
      method="POST"
      style="width:40%"
      action="Cad_Produto3.php"
      enctype="multipart/form-data">

      <table border="0">

        <!-- Título -->
        <tr>
          <td align="center" colspan="2">
            <h2><strong>Cadastro Produto</strong></h2>
          </td>
        </tr>

        <!-- Campo nome -->
        <tr>
          <td align="right" width="25%">Nome:</td>

          <td align="left" width="57%">
            <div name='user'>
              <input
                type="text"
                value=""
                name="produto"
                placeholder="Digite o nome do Produto"
                required>
            </div>
          </td>
        </tr>

        <!-- Campo descrição -->
        <tr>
          <td align="right" width="25%">Descrição:</td>

          <td align="left" width="57%">
            <div name='user'>
              <input
                type="text"
                value=""
                name="desc"
                placeholder="Digite a descrição do produto"
                required>
            </div>
          </td>
        </tr>

        <!-- Campo preço -->
        <tr>
          <td align="right" width="25%">Preço:</td>

          <td align="left" width="57%">
            <div name='user'>
              R$

              <input
                type="number"
                value=""
                name="preco"
                step="0.01"
                min="0"
                placeholder="Digite o preco do produto"
                required>
            </div>
          </td>
        </tr>

        <!-- Select de gênero -->
        <tr>
          <td align="right" width="25%">Genero:</td>

          <td align="left" width="57%">
            <div name="senha">

              <select name="genero" required>

                <option value="">Selecione o gênero</option>

                <?php

                // Busca os gêneros no banco
                $sql = "SELECT *
      FROM tb_genero";

                $r = mysqli_query($con, $sql);

                // Cria as opções do select
                while ($x = mysqli_fetch_assoc($r)) {

                  echo "<option value=" . $x["id_genero"] . ">" . $x["nome_genero"] . "</option>";
                }

                ?>

              </select>

            </div>
          </td>
        </tr>

        <!-- Select de categoria -->
        <tr>
          <td align="right" width="25%">Categoria:</td>

          <td align="left" width="57%">
            <div>

              <select name="categoria" required>

                <option value="">Selecione a categoria</option>

                <?php

                // Busca categorias no banco
                $sql = "SELECT *
      FROM tb_categoria";

                $r = mysqli_query($con, $sql);

                // Cria as opções do select
                while ($x = mysqli_fetch_assoc($r)) {

                  echo "<option value=" . $x["id_categoria"] . ">" . $x["nome_categoria"] . "</option>";
                }

                ?>

              </select>

            </div>
          </td>
        </tr>

      </table>

      <!-- Área das variações -->
      <div
        style="
background-color:lavenderblush;
padding:15px;
border-radius:10px;
">

        <label>

          Variações do produto

          <small style="color:#c02020">
            (todas devem ser preenchidas)
          </small>

        </label>

        <!-- Local onde as variações serão adicionadas -->
        <div id="variationsContainer"></div>

        <!-- Botões de controle -->
        <div style="margin-top:12px; display:flex; gap:8px;">

          <!-- Quantidade de variações -->
          <input
            id="generateCount"
            type="number"
            min="1"
            placeholder="N° variações"
            style="width:120px; padding:6px;">

          <!-- Gera várias variações -->
          <button type="button" id="btnGenerate">
            Gerar
          </button>

          <!-- Adiciona uma variação -->
          <button type="button" id="btnAdd">
            Adicionar variação
          </button>

          <!-- Remove todas as variações -->
          <button type="button" id="btnReset">
            Excluir variações
          </button>

        </div>

      </div>

      <br>

      <!-- Botão cadastrar -->
      <input
        type="submit"
        value="Cadastrar"
        name="cadastrar">

      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

      <!-- Botão limpar -->
      <input
        type="button"
        onclick="location.replace('Cad_user.php');"
        value="Limpar">

    </form>

  </center>

</body>

<!-- Template usado para criar novas variações -->
<template id="varTemplate">

  <div
    class="var-block"

    style="
border:1px solid #ccc;
padding:10px;
margin-top:10px;
border-radius:10px;
background:white;
">

    <div
      style="
display:flex;
gap:10px;
align-items:center;
flex-wrap:wrap;
">

      <!-- Select de cor -->
      <select
        name="var_cor[]"
        style="padding:6px;"
        required>

        <option value="">
          Selecione a cor
        </option>

        <option value="Preto">
          Preto
        </option>

        <option value="Branco">
          Branco
        </option>

        <option value="Azul">
          Azul
        </option>

        <option value="Vermelho">
          Vermelho
        </option>

        <option value="Verde">
          Verde
        </option>

      </select>

      <!-- Campo estoque -->
      <input
        name="var_estoque[]"
        type="number"
        placeholder="Estoque"
        min="0"
        style="width:100px; padding:6px;"
        required>

      <!-- Upload de imagens -->
      <input
        name="variation_images[__IDX__][]"
        type="file"
        multiple
        accept="image/*"
        style="width:220px;"
        required>

      <!-- Remove a variação -->
      <button
        type="button"
        class="removeBtn">
        X
      </button>

    </div>

    <!-- Área dos tamanhos -->
    <div style="margin-top:10px;">

      <strong>Tamanhos:</strong>

      <div
        style="
display:flex;
justify-content: center;
gap:10px;
margin-top:6px;
flex-wrap:wrap;
">

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

echo $data;

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
(nome_produto,descricao_produto,preco,id_categoria,id_genero,ativo,data_cadastro)
VALUES
('$produto','$desc','$preco','$categoria','$genero','1','$data')";

    mysqli_query($con, $sql);

    // Pega o ID do produto inserido
    $id_produto = mysqli_insert_id($con);

    // Pasta de uploads
    $pasta = "uploads/produtos/";

    // Cria a pasta se não existir
    if (!is_dir($pasta)) {
      mkdir($pasta, 0777, true);
    }

    // Percorre as variações
    for ($i = 0; $i < count($cores); $i++) {

      $cor = trim($cores[$i]);

      $stock = (int)$stocks[$i];

      $tamanhosSelecionados =
        $tamanhos[$i] ?? [];

      // Cria uma variação para cada tamanho
      foreach ($tamanhosSelecionados as $tamanho) {

        $tamanho = trim($tamanho);

        // Insere variação no banco
        $sql = "INSERT INTO tb_variacao_produto

(id_produto,tamanho,cor,estoque)
VALUES
('$id_produto','$tamanho','$cor',$stock)";

        mysqli_query($con, $sql);

        // Pega ID da variação
        $id_variacao = mysqli_insert_id($con);

        // Verifica se existem imagens
        if (!empty($images['name'][$i])) {

          // Percorre as imagens
          foreach (
            $images['name'][$i]
            as $idx => $filename
          ) {

            // Caminho temporário
            $tmp_name =
              $images['tmp_name'][$i][$idx];

            // Pega extensão
            $extensao =
              pathinfo(
                $filename,
                PATHINFO_EXTENSION
              );

            // Cria nome único
            $novoNome =
              uniqid() . "." . $extensao;

            // Caminho final
            $destino =
              "../produtos/uploads/" . $novoNome;

            // Cria pasta uploads
            if (!is_dir("uploads")) {

              mkdir(
                "uploads/",
                0777,
                true
              );
            }

            // Move imagem
            move_uploaded_file(
              $tmp_name,
              $destino
            );

            // Salva imagem no banco
            $sql = "INSERT INTO tb_imagem_produto

(id_variacao,caminho_imagem)
VALUES
('$id_variacao','$novoNome')";

            mysqli_query($con, $sql);
          }
        }
      }
    }

    // Mensagem de sucesso
    echo "
<font color=green size=4>
Produto Cadastrado com Sucesso
</font>
";

  } else {

    // Exibe os erros encontrados
    echo "
<font color=red size=4>
$erro
</font>
";

  }
}
?>

</html>