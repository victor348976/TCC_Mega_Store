<?php
  include("conexao.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produto</title>
</head>
<body>
    <center>
        <form id="productForm" method="POST" style="width:40%" action="Cad_Produto3.php" enctype="multipart/form-data">
            <table border="0">
                <tr>
                    <td align="center" colspan="2">
                      <h2><strong>Cadastro Produto</strong></h2>
                    </td>
                </tr>
                <tr>
                    <td align="right" width="25%">Nome:
                    </td>
                    <td align="left" width="57%">
                      <div name='user'>
                        <input type="text" value="" name="produto"
                        placeholder="Digite o nome do Produto" required>
                      </div>
                    </td>
                </tr>
                <tr>
                    <td align="right" width="25%">Descrição:
                    </td>
                    <td align="left" width="57%">
                      <div name='user'>
                        <input type="text" value="" name="desc"
                        placeholder="Digite a descrição do produto" required>
                      </div>
                    </td>
                </tr>
                <tr>
                    <td align="right" width="25%">preço:
                    </td>
                    <td align="left" width="57%">
                      <div name='user'>R$
                        <input type="number" value="" name="preco" step="0.01" min="0"
                        placeholder="Digite o preco do produto" required>
                      </div>
                    </td>
                </tr>
                <tr>
                 <td align="right" width="25%">Genero: </td>
                 <td align="left" width="57%">
                  <div name="senha">
                    <select name="genero" required>
                      <option value="">Selecione o gênero</option>
                      <?php
                      $sql="SELECT *
                            FROM   tb_genero";
                      $r= mysqli_query($con,$sql);
                      while($x = mysqli_fetch_assoc($r)){
                        echo"<option value=".$x["id_genero"].">".$x["nome_genero"]."</option>";
                      }
                      ?>
                    </select>
                  </div>
                 </td>
                </tr>
                <tr>
                 <td align="right" width="25%">Categoria: </td>
                 <td align="left" width="57%">
                  <div>
                    <select name="categoria" required>
                      <option value="">Selecione a categoria</option>
                      <?php
                      $sql="SELECT *
                            FROM   tb_categoria";
                      $r= mysqli_query($con,$sql);
                      while($x = mysqli_fetch_assoc($r)){
                        echo"<option value=".$x["id_categoria"].">".$x["nome_categoria"]."</option>";
                      }
                      ?>
                    </select>
                  </div>
                 </td>
                </tr>
            </table>
            <div style="background-color:lavenderblush">
            <label>Variações do produto <small style="color:#c02020">(todas devem ser preenchidas)</small></label>

    <div style="display:flex; gap:10px; font-weight:700; margin-bottom:6px;">
      <span style="width:110px">Tamanho</span>
      <span style="width:120px">Cor</span>
      <span style="width:90px">Estoque</span>
      <span style="width:220px">Imagens</span>
      <span style="width:40px">Ação</span>
    </div>

    <div id="variationsContainer"></div>

    <div style="margin-top:8px; display:flex; gap:8px;">
      <input id="generateCount" type="number" min="1" placeholder="N° variações" style="width:120px; padding:6px;">
      <button type="button" id="btnGenerate">Gerar</button>
      <button type="button" id="btnAdd">Adicionar variação</button>
      <button type="button" id="btnReset">Excluir variações</button>
    </div>

    <div style="margin-top:12px;">
      <button type="submit">Enviar</button>
    </div>

    <template id="varTemplate">
      <div class="var-block" style="display:flex; gap:8px; align-items:center; margin-top:8px;">
        <input name="var_tamanho[]" placeholder="M" style="width:110px; padding:6px;">
        <input type="color" name="var_cor[]" value="#000000" style="width:120px; height:35px; padding:2px;">
        <input name="var_estoque[]" type="number" placeholder="0" min="0" style="width:90px; padding:6px;">
        <input name="variation_images[__IDX__][]" type="file" multiple accept="image/*" style="width:220px;">
        <button type="button" class="removeBtn">X</button>
      </div>
    </template>
    </div>
            <table border="0">
                 <tr>
                 <td align="center" colspan="2">
                  <br>
                  <input type="submit" value="Cadastrar" name="cadastrar">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="button" onclick="location.replace('Cad_user.php');" value="Limpar"><br>
                 </td>
                </tr>
            </table>
        </form>
    </body>
    <script>
let varIndex = 0;

function addVariation(tamanho = '', cor = '', stock = '') {
  const tpl = document.getElementById('varTemplate').innerHTML;
  const html = tpl.replace(/__IDX__/g, varIndex);
  varIndex++;

  const tmp = document.createElement('div');
  tmp.innerHTML = html;
  const block = tmp.firstElementChild;

  const inputs = block.querySelectorAll('input');
  if (inputs[0]) inputs[0].value = tamanho;
  if (inputs[1]) inputs[1].value = cor;
  if (inputs[2]) inputs[2].value = stock;

  block.querySelector('.removeBtn').addEventListener('click', () => block.remove());

  document.getElementById('variationsContainer').appendChild(block);
}

function resetVariations() {
  document.getElementById('variationsContainer').innerHTML = '';
  varIndex = 0;
}

function generateVariations(n) {
  for (let i = 0; i < n; i++) addVariation();
}

function allVariationsValid() {
  const blocks = document.querySelectorAll('#variationsContainer .var-block');
  if (blocks.length === 0) return false;

  let hasImage = false;

  for (const b of blocks) {
    const tamanho = b.querySelector('input[name="var_tamanho[]"]').value.trim();
    const cor = b.querySelector('input[name="var_cor[]"]').value;
    const stock = parseInt(b.querySelector('input[name="var_estoque[]"]').value || '0', 10);
    const files = b.querySelector('input[type="file"]').files.length;

    if (tamanho === '' || !cor || !Number.isFinite(stock) || stock <= 0) {
    return false;
  }
    if (files > 0) hasImage = true;
  }
  return hasImage;
}

document.getElementById('btnAdd').addEventListener('click', () => addVariation());
document.getElementById('btnReset').addEventListener('click', () => resetVariations());
document.getElementById('btnGenerate').addEventListener('click', () => {
  const n = parseInt(document.getElementById('generateCount').value || '0', 10);
  if (n > 0) generateVariations(n);
});

document.getElementById('productForm').addEventListener('submit', function(e) {
  if (!allVariationsValid()) {
    e.preventDefault();
    alert("Erro: todas as variações devem ter tamanho, cor, estoque > 0 e pelo menos uma imagem.");
  }
});

addVariation();
</script>
    <?php
$data = date('Y/m/d');
echo $data;

if (isset($_POST["cadastrar"])) {

    $produto   = $_POST["produto"] ?? [];
    $desc      = $_POST["desc"] ?? [];
    $preco     = $_POST["preco"] ?? [];
    $genero    = $_POST["genero"] ?? [];
    $categoria = $_POST["categoria"] ?? [];
    
    $tamanhos  = $_POST['var_tamanho'] ?? [];
    $cores     = $_POST['var_cor'] ?? [];
    $stocks    = $_POST['var_estoque'] ?? [];
    $images    = $_FILES['variation_images'] ?? [];
    $temImagem = false;
    $erro = '';
    for ($i = 0; $i < count($tamanhos); $i++) {
        $tamanho  = trim($tamanhos[$i]);
        $cor = trim($cores[$i]);
        $stock = (int)$stocks[$i];

        if ($tamanho === '') {
            $erro .= "Digite o tamanho do produto [".$i."]<br>";
        }
        if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $cor)) {
             $erro .= "Cor inválida na variação [".$i."]<br>";
        }
        if ($stock === '') {
            $erro .= "Digite o estoque do produto [".$i."]<br>";
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

    if ($erro == '') {
        $sql = "INSERT INTO tb_produto 
        (nome_produto, descricao_produto, preco, id_categoria, id_genero, ativo, data_cadastro) 
        VALUES ('$produto', '$desc', '$preco', '$categoria', '$genero', '1', '$data')";
        mysqli_query($con, $sql);

        $id_produto = mysqli_insert_id($con);

        $pasta = "uploads/produtos/";
        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        for ($i = 0; $i < count($tamanhos); $i++) {
        $tamanho  = trim($tamanhos[$i]);
        $cor = trim($cores[$i]);
        $stock = (int)$stocks[$i];
            $sql = "INSERT INTO tb_variacao_produto (id_produto, tamanho, cor, estoque) 
                    VALUES ('$id_produto', '$tamanho', '$cor', $stock)";
              mysqli_query($con, $sql);
                
              $id_variacao = mysqli_insert_id($con);

                // salva imagens
                if (!empty($images['name'][$i])) {
                    foreach ($images['name'][$i] as $idx => $filename) {

    // Caminho temporário do arquivo
    $tmp_name = $images['tmp_name'][$i][$idx];

    // Pega a extensão original (jpg, png, etc.)
    $extensao = pathinfo($filename, PATHINFO_EXTENSION);

    // Gera um nome único para o arquivo
    $novoNome = uniqid() . "." . $extensao;

    // Define o destino final
    $destino = "../uploads/produtos/" . $novoNome;

    // Garante que a pasta exista
    if (!is_dir("uploads")) {
        mkdir("uploads/produtos/", 0777, true);
    }

    // Move o arquivo para a pasta final
    move_uploaded_file($tmp_name, $destino);

    // Salva o caminho no banco ligado à variação
    $sql = "INSERT INTO tb_imagem_produto (id_variacao, caminho_imagem)
            VALUES ('$id_variacao', '$novoNome')";
    mysqli_query($con, $sql);
}

                }
        }

        /*$imagem = $_FILES['imagem'];
        $nomeOriginal = $imagem['name'];
        $tmp = $imagem['tmp_name'];
        $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);

        $novoNome = uniqid() . "." . $extensao;

        move_uploaded_file($tmp, $pasta . $novoNome);

        $sql = "INSERT INTO tb_imagem_produto (id_variacao, caminho_imagem)
                VALUES ('$id_variacao', '$novoNome')";
        mysqli_query($con, $sql);*/

        echo "<font cor=green tamanho=4>Produto Cadastrado com Sucesso</font>";
    } else {
        echo "<font cor=red tamanho=4>$erro</font>";
    }
}
?>

    </center>
</html>