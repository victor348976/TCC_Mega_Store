<?php
include("../geral/conexao.php");
include("../geral/head.php");

$id_produto = isset($_GET['id_produto']) ? (int)$_GET['id_produto'] : 0;

$sqlProduto = "SELECT p.*, ct.nome_categoria, gen.nome_genero
               FROM tb_produto p
               LEFT JOIN tb_categoria ct ON ct.id_categoria = p.id_categoria
               LEFT JOIN tb_genero gen ON gen.id_genero = p.id_genero
               WHERE p.id_produto = $id_produto
               LIMIT 1";
$rProduto = mysqli_query($con, $sqlProduto);
$prod = mysqli_fetch_assoc($rProduto);

$variacoes = [];
$imagemInicial = '';

$sqlVar = "SELECT vp.cor, vp.tamanho, vp.estoque, ip.caminho_imagem
           FROM tb_variacao_produto vp
           LEFT JOIN tb_imagem_produto ip ON ip.id_variacao = vp.id_variacao
           WHERE vp.id_produto = $id_produto
           ORDER BY vp.id_variacao";
$rVar = mysqli_query($con, $sqlVar);

while ($v = mysqli_fetch_assoc($rVar)) {
    $cor = $v['cor'];
    $tamanho = $v['tamanho'];
    $estoque = (int)$v['estoque'];
    $imagem = $v['caminho_imagem'] ?? '';

    if (!isset($variacoes[$cor])) {
        $variacoes[$cor] = [
            'imagem' => '',
            'tamanhos' => []
        ];
    }

    if ($variacoes[$cor]['imagem'] === '' && $imagem !== '') {
        $variacoes[$cor]['imagem'] = $imagem;
        if ($imagemInicial === '') {
            $imagemInicial = $imagem;
        }
    }

    $variacoes[$cor]['tamanhos'][$tamanho] = ($estoque > 0);
}

$coresDisponiveis = array_keys($variacoes);
if ($imagemInicial === '' && !empty($coresDisponiveis)) {
    $primeiraCor = $coresDisponiveis[0];
    $imagemInicial = $variacoes[$primeiraCor]['imagem'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Produto</title>
    <style>
        body {
            font-family: Arial;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #ccc;
            width: 700px;
            padding: 10px;
            display: flex;
            gap: 40px;
            justify-content: center;
        }

        .imagem img {
            width: 350px;
            height: 450px;
            object-fit: cover;
            border-radius: 10px;
        }

        .info {
            max-width: 400px;
        }

        .preco {
            font-size: 22px;
            margin: 10px 0;
        }

        .descricao {
            margin: 20px 0;
            color: #555;
        }

        .cores label {
            display: inline-block;
            margin-right: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            padding: 6px 10px;
            border: 1px solid #000;
            border-radius: 6px;
            background: #fff;
        }

        .cores input {
            margin-right: 6px;
        }

        .tamanhos {
            margin-top: 10px;
        }

        .tamanho-btn {
            border: 1px solid #000;
            padding: 8px 12px;
            margin-right: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            background: #fff;
            border-radius: 6px;
        }

        .tamanho-btn.ativo {
            background: black;
            color: white;
        }

        .tamanho-btn.indisponivel {
            background: #666;
            color: #ddd;
            cursor: not-allowed;
            opacity: 0.5;
        }

        .botao {
            margin-top: 20px;
            padding: 12px;
            background: black;
            color: white;
            text-align: center;
            cursor: pointer;
            border-radius: 8px;
        }

        .relacionados {
            margin-top: 50px;
        }

        .cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            border: 1px solid #ddd;
            padding: 10px;
            width: 150px;
            cursor: pointer;
        }

        .card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
        }

        .container-produtos {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            padding: 20px;
        }

        .card-produto {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            background: #fff;
            transition: 0.3s;
        }

        .card-produto:hover {
            transform: scale(1.05);
        }

        .card-produto img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }

        .card-produto h3 {
            font-size: 16px;
            margin: 10px 0 5px;
        }

        .card-produto .preco {
            color: green;
            font-weight: bold;
        }

        @media (max-width: 1000px) {
            .container-produtos {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 600px) {
            .container-produtos {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>
<center>
<?php if ($prod): ?>
    <div class="container">
        <div class="imagem">
            <img id="imagem-produto" src="../produtos/uploads/<?php echo htmlspecialchars($imagemInicial); ?>" alt="Imagem do produto">
        </div>

        <div class="info">
            <h2><?php echo htmlspecialchars($prod['nome_produto']); ?></h2>
            <div class="preco">R$ <?php echo htmlspecialchars($prod['preco']); ?></div>

            <div class="descricao">
                <?php echo htmlspecialchars($prod['descricao_produto']); ?>
            </div>

            <div class="cores">
                <strong>Cor:</strong><br><br>
                <?php foreach ($variacoes as $cor => $dados): ?>
                    <label>
                        <input
                            type="radio"
                            name="cor"
                            value="<?php echo htmlspecialchars($cor); ?>"
                            data-img="../produtos/uploads/<?php echo htmlspecialchars($dados['imagem']); ?>"
                            onclick="selecionarCor(this)">
                        <?php echo htmlspecialchars($cor); ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <div class="tamanhos">
                <strong>Tamanho:</strong><br><br>
                <div id="lista-tamanhos"></div>
            </div>

            <div class="botao" onclick="comprar()">COMPRAR AGORA</div>
        </div>
    </div>
<?php endif; ?>

<?php
$sqlRel = "SELECT p.id_produto, p.nome_produto, p.preco, MIN(ip.caminho_imagem) AS caminho_imagem
           FROM tb_produto p
           LEFT JOIN tb_variacao_produto vp ON vp.id_produto = p.id_produto
           LEFT JOIN tb_imagem_produto ip ON ip.id_variacao = vp.id_variacao
           WHERE p.id_produto <> $id_produto
           GROUP BY p.id_produto, p.nome_produto, p.preco
           ORDER BY p.id_produto DESC
           LIMIT 10";
$rRel = mysqli_query($con, $sqlRel);
?>

<div class="relacionados">
    <h3>Produtos relacionados</h3>
    <div class="container-produtos">
        <?php while ($x = mysqli_fetch_assoc($rRel)): ?>
            <form method="get" action="Card_venda_produto.php">
                <input type="hidden" name="id_produto" value="<?php echo $x['id_produto']; ?>">
                <button type="submit" style="border:none; background:none; padding:0; width:100%;">
                    <div class="card-produto">
                        <img src="../produtos/uploads/<?php echo htmlspecialchars($x['caminho_imagem'] ?? ''); ?>" alt="Imagem do produto">
                        <h3><?php echo htmlspecialchars($x['nome_produto']); ?></h3>
                        <p class="preco">R$ <?php echo htmlspecialchars($x['preco']); ?></p>
                    </div>
                </button>
            </form>
        <?php endwhile; ?>
    </div>
</div>

<script>
    const variacoes = <?php echo json_encode($variacoes); ?>;
    const tamanhosFixos = ["PP", "P", "M", "G", "GG", "XG"];

    let corSelecionada = null;
    let tamanhoSelecionado = null;

    function selecionarCor(el) {
        corSelecionada = el.value;
        tamanhoSelecionado = null;

        const novaImagem = el.getAttribute("data-img");
        if (novaImagem) {
            document.getElementById("imagem-produto").src = novaImagem;
        }

        renderTamanhos(corSelecionada);
    }

    function renderTamanhos(cor) {
        const container = document.getElementById("lista-tamanhos");

        if (!cor || !variacoes[cor]) {
            container.innerHTML = "";
            return;
        }

        let html = "";

        tamanhosFixos.forEach(tam => {
            const disponivel = variacoes[cor].tamanhos[tam] === true;

            html += `
                <button
                    type="button"
                    class="tamanho-btn ${disponivel ? "disponivel" : "indisponivel"}"
                    data-tam="${tam}"
                    ${disponivel ? "" : "disabled"}>
                    ${tam}
                </button>
            `;
        });

        container.innerHTML = html;

        container.querySelectorAll(".tamanho-btn.disponivel").forEach(btn => {
            btn.addEventListener("click", function () {
                selecionarTamanho(this);
            });
        });
    }

    function selecionarTamanho(el) {
        document.querySelectorAll(".tamanho-btn").forEach(e => e.classList.remove("ativo"));
        el.classList.add("ativo");
        tamanhoSelecionado = el.getAttribute("data-tam");
    }

    function comprar() {
        if (!corSelecionada) {
            alert("Selecione uma cor!");
            return;
        }

        if (!tamanhoSelecionado) {
            alert("Selecione um tamanho disponível!");
            return;
        }

        alert("Comprado! Cor: " + corSelecionada + " | Tamanho: " + tamanhoSelecionado);
    }

    window.addEventListener("DOMContentLoaded", function () {
        const primeiroRadio = document.querySelector('input[name="cor"]');
        if (primeiroRadio) {
            primeiroRadio.checked = true;
            selecionarCor(primeiroRadio);
        }
    });
</script>
</center>
</body>
</html>