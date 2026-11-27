<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel='stylesheet' href='finalizar.css'>
</head>
<body>
    
<div style="background-color: #f0f0f000; padding: 10px">
    <h1>Finalizar Compra</h1>
</div>
<?php
include("../geral/conexao.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    die("Você precisa estar logado para finalizar a compra.");
}

$id_usuario = (int) $_SESSION['id_usuario'];

$erro = "";
$pagamento = isset($_GET['pagamento']) ? (int) $_GET['pagamento'] : 0;

/* Recupera os IDs das variações selecionadas */
$ids = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids = $_POST['itens'] ?? [];
} else {
    $ids = $_GET['itens'] ?? [];
}

if (!is_array($ids)) {
    $ids = [];
}

$ids = array_values(array_unique(array_filter(
    array_map('intval', $ids),
    function ($id) {
        return $id > 0;
    }
)));

/* Busca os produtos selecionados no carrinho */
function buscarItensCarrinho($con, $id_usuario, $ids)
{
    if (empty($ids)) {
        return [];
    }

    $listaIds = implode(',', $ids);

    $sql = "SELECT
                p.id_produto,
                p.nome_produto,
                p.preco,
                ct.nome_categoria,
                gen.nome_genero,
                vp.id_variacao,
                vp.cor,
                vp.tamanho,
                c.qtd_carrinho,
                (
                    SELECT ip.caminho_imagem
                    FROM tb_imagem_produto ip
                    WHERE ip.id_variacao = vp.id_variacao
                    LIMIT 1
                ) AS imagem
            FROM tb_carrinho c
            INNER JOIN tb_variacao_produto vp
                ON vp.id_variacao = c.id_variacao
            INNER JOIN tb_produto p
                ON p.id_produto = vp.id_produto
            LEFT JOIN tb_categoria ct
                ON ct.id_categoria = p.id_categoria
            LEFT JOIN tb_genero gen
                ON gen.id_genero = p.id_genero
            WHERE c.id_usuario = ?
            AND vp.id_variacao IN ($listaIds)";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);
    $itens = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);

    return $itens;
}

/* Carrega os dados do usuário */
$sqlUsuario = "SELECT nome_usuario, email, cpf, numero
               FROM tb_usuario
               WHERE id_usuario = ?";

$stmtUsuario = mysqli_prepare($con, $sqlUsuario);
mysqli_stmt_bind_param($stmtUsuario, "i", $id_usuario);
mysqli_stmt_execute($stmtUsuario);

$usuario = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmtUsuario)
);

mysqli_stmt_close($stmtUsuario);

/* Carrega o endereço mais recente */
$sqlEndereco = "SELECT *
                FROM tb_endereco
                WHERE id_usuario = ?
                ORDER BY id_endereco DESC
                LIMIT 1";

$stmtEndereco = mysqli_prepare($con, $sqlEndereco);
mysqli_stmt_bind_param($stmtEndereco, "i", $id_usuario);
mysqli_stmt_execute($stmtEndereco);

$endereco = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmtEndereco)
);

mysqli_stmt_close($stmtEndereco);

/* Valores iniciais dos campos */
$nome = $usuario['nome_usuario'] ?? '';
$email = $usuario['email'] ?? '';
$cpf = $usuario['cpf'] ?? '';
$telefone = $usuario['numero'] ?? '';

$rua = $endereco['rua'] ?? '';
$numero = $endereco['numero'] ?? '';
$bairro = $endereco['bairro'] ?? '';
$cidade = $endereco['cidade'] ?? '';
$estado = $endereco['estado'] ?? '';
$cep = $endereco['cep'] ?? '';
$complemento = $endereco['complemento'] ?? '';

$formaPagamento = 0;

/* Processamento da compra */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$pagamento) {

    /* Recupera os dados enviados */
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cpf = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
    $telefone = preg_replace('/\D/', '', $_POST['telefone'] ?? '');

    $rua = trim($_POST['rua'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $bairro = trim($_POST['bairro'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $estado = strtoupper(trim($_POST['estado'] ?? ''));
    $cep = preg_replace('/\D/', '', $_POST['cep'] ?? '');
    $complemento = trim($_POST['complemento'] ?? '');

    $formaPagamento = (int) ($_POST['forma_pagamento'] ?? 0);

    /* Validação dos dados pessoais */
    if (
        $nome === '' ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        strlen($cpf) !== 11 ||
        !in_array(strlen($telefone), [10, 11])
    ) {
        $erro = "Confira os dados pessoais informados.";
    }

    /* Validação do endereço */
    elseif (
        $rua === '' ||
        !ctype_digit($numero) ||
        $bairro === '' ||
        $cidade === '' ||
        strlen($estado) !== 2 ||
        strlen($cep) !== 8
    ) {
        $erro = "Confira os dados do endereço.";
    }

    /* Validação da forma de pagamento */
    elseif (!in_array($formaPagamento, [1, 2, 3, 4])) {
        $erro = "Selecione uma forma de pagamento válida.";
    }

    /* Validação demonstrativa dos cartões */
if ($erro === '' && in_array($formaPagamento, [2, 3])) {

    $titular = trim($_POST['titular'] ?? '');
    $cartao = preg_replace('/\D/', '', $_POST['cartao'] ?? '');
    $validade = trim($_POST['validade'] ?? '');
    $cvv = preg_replace('/\D/', '', $_POST['cvv'] ?? '');

    if (
        $titular === '' ||
        strlen($cartao) !== 16 ||
        !preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $validade) ||
        !in_array(strlen($cvv), [3, 4])
    ) {
        $erro = "Confira os dados do cartão.";
    } else {
        [$mes, $ano] = explode('/', $validade);

        $anoCompleto = 2000 + (int) $ano;
        $dataValidade = $anoCompleto * 100 + (int) $mes;
        $dataAtual = (int) date('Y') * 100 + (int) date('m');

        if ($dataValidade < $dataAtual) {
            $erro = "O cartão está com a validade vencida.";
        }
    }
}

    /* Confere os produtos novamente no banco */
    if ($erro === '' && empty($ids)) {
        $erro = "Nenhum produto foi selecionado.";
    }

    $itens = [];

    if ($erro === '') {
        $itens = buscarItensCarrinho($con, $id_usuario, $ids);

        if (count($itens) !== count($ids)) {
            $erro = "Um ou mais produtos não estão mais disponíveis no carrinho.";
        }
    }

    /* Calcula o total usando os valores do banco */
    $total = 0;

    foreach ($itens as $item) {
        $total += (float) $item['preco'] * (int) $item['qtd_carrinho'];
    }



    /* Salva a compra */
    if ($erro === '') {

        mysqli_begin_transaction($con);

        try {

            /* Atualiza os dados pessoais do usuário */
            $sql = "UPDATE tb_usuario
                    SET nome_usuario = ?,
                        email = ?,
                        cpf = ?,
                        numero = ?
                    WHERE id_usuario = ?";

            $stmt = mysqli_prepare($con, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "ssssi",
                $nome,
                $email,
                $cpf,
                $telefone,
                $id_usuario
            );

            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            /*
             * Cria um novo registro de endereço para esta compra.
             * Assim, o endereço utilizado em uma venda fica preservado.
             */
            $sql = "INSERT INTO tb_endereco
                    (id_usuario, rua, numero, bairro, cidade,
                     estado, cep, complemento)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($con, $sql);

            $numeroEndereco = (int) $numero;

            mysqli_stmt_bind_param(
                $stmt,
                "isisssss",
                $id_usuario,
                $rua,
                $numeroEndereco,
                $bairro,
                $cidade,
                $estado,
                $cep,
                $complemento
            );

            mysqli_stmt_execute($stmt);

            $id_endereco = mysqli_insert_id($con);

            mysqli_stmt_close($stmt);

            /* Registra a venda */
            $dataPedido = date('Y-m-d');
            $statusVenda = "Aguardando pagamento";

            $sql = "INSERT INTO tb_venda
                    (id_usuario, id_endereco, data_pedido,
                     status_venda, valor_total)
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($con, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "iissd",
                $id_usuario,
                $id_endereco,
                $dataPedido,
                $statusVenda,
                $total
            );

            mysqli_stmt_execute($stmt);

            $id_venda = mysqli_insert_id($con);

            mysqli_stmt_close($stmt);

            /* Registra cada produto da venda */
            $sql = "INSERT INTO tb_produto_venda
                    (id_venda, id_variacao, quantidade, preco_unitario)
                    VALUES (?, ?, ?, ?)";

            $stmtProduto = mysqli_prepare($con, $sql);

            foreach ($itens as $item) {

                $id_variacao = (int) $item['id_variacao'];
                $quantidade = (int) $item['qtd_carrinho'];
                $precoUnitario = (float) $item['preco'];

                mysqli_stmt_bind_param(
                    $stmtProduto,
                    "iiid",
                    $id_venda,
                    $id_variacao,
                    $quantidade,
                    $precoUnitario
                );

                mysqli_stmt_execute($stmtProduto);
            }

            mysqli_stmt_close($stmtProduto);

            /* Registra o pagamento como pendente */
$statusPagamento = 0;
$dataPagamento = null;

$sql = "INSERT INTO tb_pagamento
        (id_venda, id_forma_pagamento,
         status_pagamento, data_pagamento)
        VALUES (?, ?, ?, ?)";

$stmtPagamento = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param(
    $stmtPagamento,
    "iiis",
    $id_venda,
    $formaPagamento,
    $statusPagamento,
    $dataPagamento
);

mysqli_stmt_execute($stmtPagamento);
mysqli_stmt_close($stmtPagamento);

            /* Remove somente os produtos comprados do carrinho */
            $listaIds = implode(',', $ids);

            $sql = "DELETE FROM tb_carrinho
                    WHERE id_usuario = ?
                    AND id_variacao IN ($listaIds)";

            $stmt = mysqli_prepare($con, $sql);

            mysqli_stmt_bind_param($stmt, "i", $id_usuario);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            mysqli_commit($con);

           header("Location: finalizar_compra.php?pagamento=" . $id_venda);
            exit;

        } catch (Throwable $e) {

            mysqli_rollback($con);

            $erro = "Não foi possível finalizar a compra. Tente novamente.";

            // Para depuração durante o desenvolvimento:
            // $erro = $e->getMessage();
        }
    }
}

$dadosPagamento = null;

if ($pagamento > 0) {

    $sql = "SELECT
                v.id_venda,
                v.valor_total,
                v.status_venda,
                pg.id_forma_pagamento,
                pg.status_pagamento
            FROM tb_venda v
            INNER JOIN tb_pagamento pg
                ON pg.id_venda = v.id_venda
            WHERE v.id_venda = ?
            AND v.id_usuario = ?
            LIMIT 1";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ii",
        $pagamento,
        $id_usuario
    );

    mysqli_stmt_execute($stmt);

    $dadosPagamento = mysqli_fetch_assoc(
        mysqli_stmt_get_result($stmt)
    );

    mysqli_stmt_close($stmt);
}

/* Busca os produtos para exibição */
$itens = [];

if (!$pagamento && !empty($ids)) {
        $itens = buscarItensCarrinho($con, $id_usuario, $ids);
}

$total = 0;

foreach ($itens as $item) {
    $total += (float) $item['preco'] * (int) $item['qtd_carrinho'];
}

include("../geral/head.php");
?>

<style>
    .checkout {
        display: grid;
        grid-template-columns: 1.5fr 0.8fr;
        gap: 30px;
        max-width: 1250px;
        margin: 40px auto;
        padding: 20px;
    }

    .checkout h2 {
        margin-bottom: 20px;
    }

    .checkout-section {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 20px;
    }

    .checkout-section h3 {
        margin-bottom: 20px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .campo {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .campo.completo {
        grid-column: 1 / -1;
    }

    .campo label {
        font-size: 14px;
        font-weight: 600;
    }

    .campo input,
    .campo select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
    }

    .pagamento-opcoes {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 20px;
    }

    .pagamento-opcoes label {
        border: 1px solid #ccc;
        border-radius: 7px;
        padding: 15px;
        cursor: pointer;
    }

    .pagamento-opcoes input {
        margin-right: 8px;
    }

    .dados-cartao {
        display: none;
        margin-top: 20px;
    }

    .produto-resumo {
        display: flex;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #eee;
    }

    .produto-resumo img {
        width: 75px;
        height: 90px;
        object-fit: cover;
        border-radius: 5px;
    }

    .produto-resumo p {
        margin: 4px 0;
        font-size: 13px;
    }

    .resumo {
        position: sticky;
        top: 20px;
        height: fit-content;
    }

    .resumo-total {
        display: flex;
        justify-content: space-between;
        font-size: 18px;
        font-weight: bold;
        margin: 20px 0;
    }

    .btn-finalizar {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 7px;
        background: #222;
        color: white;
        font-size: 15px;
        font-weight: bold;
        cursor: pointer;
    }

    .btn-finalizar:hover {
        background: #444;
    }

    .mensagem-erro {
        max-width: 1200px;
        margin: 20px auto;
        padding: 15px;
        background: #ffe5e5;
        color: #a00000;
        border-radius: 6px;
    }

    .sucesso {
        max-width: 700px;
        margin: 70px auto;
        padding: 35px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 10px;
    }

    .sucesso h2 {
        margin-bottom: 15px;
    }

    @media (max-width: 800px) {
        .checkout {
            grid-template-columns: 1fr;
        }

        .resumo {
            position: static;
        }
    }

    @media (max-width: 500px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .campo.completo {
            grid-column: auto;
        }
    }
</style>


<?php if ($pagamento && $dadosPagamento): ?>
        <?php if ((int) $dadosPagamento['status_pagamento'] === 1): ?>

    <div class="sucesso">

        <h2 style="color: green;">Pagamento confirmado!</h2>

        <p>Seu pagamento foi aprovado pelo administrador.</p>

        <p>Obrigado por comprar na Mega Store!</p>

        <p>Você será redirecionado para o carrinho em alguns segundos.</p>

        <a href="NOME_DA_TELA_DO_CARRINHO.php">
            Voltar ao carrinho
        </a>

    </div>

    <script>
        setTimeout(function () {
            window.location.href = "NOME_DA_TELA_DO_CARRINHO.php";
        }, 5000);
    </script>

<?php else: ?>
    <div class="sucesso">

        <h2>Pedido registrado!</h2>

        <p>
            Número do pedido:
            <strong>#<?= $pagamento ?></strong>
        </p>

        <p>
            Total:
            <strong>
                R$ <?= number_format(
                    $dadosPagamento['valor_total'],
                    2,
                    ',',
                    '.'
                ) ?>
            </strong>
        </p>

        <p>
            Status:
            <strong>Aguardando pagamento</strong>
        </p>

        <?php if ((int) $dadosPagamento['id_forma_pagamento'] === 4): ?>

            <h3>Pagamento via PIX</h3>

            <p>Escaneie o QR Code abaixo para realizar o pagamento.</p>

            <img
                src="qrcode.png"
                alt="QR Code PIX"
                style="width:250px; max-width:100%; margin:20px auto;"
            >

            <p><strong>PIX copia e cola:</strong></p>

            <textarea
                id="codigoPix"
                readonly
                style="width:100%; min-height:100px; padding:10px;"
            >00020126330014BR.GOV.BCB.PIX01114473877680052040000530398654041.005802BR5920VICTOR LUIZ BERNARDO6009SAO PAULO6226052241nmOhpINneWuHcZOUNAq26304E41F</textarea>

            <button
                type="button"
                onclick="copiarPix()"
                class="btn-finalizar"
                style="margin-top:10px;"
            >
                Copiar código PIX
            </button>

            <p style="font-size:13px; margin-top:15px;">
                Após realizar o pagamento, aguarde a confirmação
                do administrador da loja.
            </p>

        <?php elseif ((int) $dadosPagamento['id_forma_pagamento'] === 1): ?>

            <h3>Boleto demonstrativo</h3>

            <div style="
                border:1px dashed #999;
                padding:20px;
                margin-top:20px;
                text-align:left;
            ">

                <h2 style="text-align:center;">
                    MEGA STORE
                </h2>

                <p>
                    <strong>Pedido:</strong>
                    #<?= $pagamento ?>
                </p>

                <p>
                    <strong>Valor:</strong>
                    R$ <?= number_format(
                        $dadosPagamento['valor_total'],
                        2,
                        ',',
                        '.'
                    ) ?>
                </p>

                <p>
                    <strong>Vencimento:</strong>
                    <?= date('d/m/Y', strtotime('+3 days')) ?>
                </p>

                <p>
                    <strong>Linha digitável:</strong>
                </p>

                <p style="
                    word-break:break-all;
                    font-family:monospace;
                ">
                    00000.00000 00000.000000 00000.000000 0 00000000000000
                </p>

                <p style="text-align:center; font-size:12px;">
                    CÓDIGO FICTÍCIO PARA DEMONSTRAÇÃO
                </p>

            </div>

            <p style="color:#a00000; margin-top:15px;">
                <strong>
                    ATENÇÃO: ESTE BOLETO NÃO POSSUI VALOR PARA PAGAMENTO.
                </strong>
            </p>

                <?php endif; ?>

    </div>

<?php endif; ?>

<?php elseif ($pagamento): ?>

    <div class="sucesso">
        <h2>Pedido não encontrado</h2>
        <p>Não foi possível localizar esse pedido.</p>
    </div>


<?php elseif (empty($itens)): ?>

    <div class="sucesso">
        <h2>Nenhum produto selecionado</h2>
        <p>Volte ao carrinho e selecione os produtos que deseja comprar.</p>
    </div>

<?php else: ?>

    <?php if ($erro !== ''): ?>
        <div class="mensagem-erro">
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="checkout">

        <div>

            <h2>Finalizar compra</h2>

            <?php foreach ($ids as $id): ?>
                <input
                    type="hidden"
                    name="itens[]"
                    value="<?= (int) $id ?>"
                >
            <?php endforeach; ?>

            <!-- DADOS PESSOAIS -->
            <section class="checkout-section">

                <h3>Dados pessoais</h3>

                <div class="form-grid">

                    <div class="campo completo">
                        <label>Nome completo</label>
                        <input
                            type="text"
                            name="nome"
                            value="<?= htmlspecialchars($nome) ?>"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label>E-mail</label>
                        <input
                            type="email"
                            name="email"
                            value="<?= htmlspecialchars($email) ?>"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label>Telefone</label>
                        <input
                            type="text"
                            name="telefone"
                            value="<?= htmlspecialchars($telefone) ?>"
                            maxlength="11"
                            required
                        >
                    </div>

                    <div class="campo completo">
                        <label>CPF</label>
                        <input
                            type="text"
                            name="cpf"
                            value="<?= htmlspecialchars($cpf) ?>"
                            maxlength="11"
                            placeholder="Somente números"
                            required
                        >
                    </div>

                </div>

            </section>

            <!-- ENDEREÇO -->
            <section class="checkout-section">

                <h3>Endereço de entrega</h3>

                <div class="form-grid">

                    <div class="campo completo">
                        <label>Rua</label>
                        <input
                            type="text"
                            name="rua"
                            value="<?= htmlspecialchars($rua) ?>"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label>Número</label>
                        <input
                            type="number"
                            name="numero"
                            value="<?= htmlspecialchars((string) $numero) ?>"
                            min="1"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label>Bairro</label>
                        <input
                            type="text"
                            name="bairro"
                            value="<?= htmlspecialchars($bairro) ?>"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label>Cidade</label>
                        <input
                            type="text"
                            name="cidade"
                            value="<?= htmlspecialchars($cidade) ?>"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label>Estado</label>
                        <input
                            type="text"
                            name="estado"
                            value="<?= htmlspecialchars($estado) ?>"
                            maxlength="2"
                            placeholder="SP"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label>CEP</label>
                        <input
                            type="text"
                            name="cep"
                            value="<?= htmlspecialchars($cep) ?>"
                            maxlength="8"
                            placeholder="Somente números"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label>Complemento</label>
                        <input
                            type="text"
                            name="complemento"
                            value="<?= htmlspecialchars($complemento) ?>"
                        >
                    </div>

                </div>

            </section>

            <!-- PAGAMENTO -->
            <section class="checkout-section">

                <h3>Forma de pagamento</h3>

                <div class="pagamento-opcoes">


    <label>
        <input
            type="radio"
            name="forma_pagamento"
            value="4"
            <?= $formaPagamento === 4 ? 'checked' : '' ?>
            required
        >
        PIX
    </label>

    <label>
        <input
            type="radio"
            name="forma_pagamento"
            value="1"
            <?= $formaPagamento === 1 ? 'checked' : '' ?>
        >
        Boleto (demonstração)
    </label>

    <label>
        <input
            type="radio"
            name="forma_pagamento"
            value="2"
            <?= $formaPagamento === 2 ? 'checked' : '' ?>
        >
        Cartão de crédito
    </label>

    <label>
        <input
            type="radio"
            name="forma_pagamento"
            value="3"
            <?= $formaPagamento === 3 ? 'checked' : '' ?>
        >
        Cartão de débito
    </label>

</div>


                <div class="dados-cartao" id="dadosCartao">

                    <div class="form-grid">

                        <div class="campo completo">
                            <label>Nome do titular</label>
                            <input
                                type="text"
                                name="titular"
                                id="titular"
                                placeholder="Nome impresso no cartão"
                            >
                        </div>

                        <div class="campo completo">
                            <label>Número do cartão</label>
                            <input
                                type="text"
                                name="cartao"
                                id="cartao"
                                maxlength="19"
                                placeholder="0000 0000 0000 0000"
                            >
                        </div>

                        <div class="campo">
                            <label>Validade</label>
                            <input
                                type="text"
                                name="validade"
                                id="validade"
                                maxlength="5"
                                placeholder="MM/AA"
                            >
                        </div>

                        <div class="campo">
                            <label>CVV</label>
                            <input
                                type="password"
                                name="cvv"
                                id="cvv"
                                maxlength="4"
                                placeholder="000"
                            >
                        </div>

                    </div>

                    <p style="font-size: 12px; margin-top: 15px;">
                        Pagamento demonstrativo. Os dados do cartão não
                        serão armazenados.
                    </p>

                </div>

            </section>

        </div>

        <!-- RESUMO -->
        <aside class="checkout-section resumo">

            <h3>Resumo do pedido</h3>

            <?php foreach ($itens as $item): ?>

                <div class="produto-resumo">

                    <?php if (!empty($item['imagem'])): ?>
                        <img
                            src="../produtos/uploads/<?= htmlspecialchars($item['imagem']) ?>"
                            alt="Produto"
                        >
                    <?php endif; ?>

                    <div>

                        <strong>
                            <?= htmlspecialchars($item['nome_produto']) ?>
                        </strong>

                        <p>
                            <?= htmlspecialchars($item['nome_categoria'] ?? '') ?>
                            - <?= htmlspecialchars($item['nome_genero'] ?? '') ?>
                        </p>

                        <p>
                            Cor: <?= htmlspecialchars($item['cor']) ?>
                            | Tamanho: <?= htmlspecialchars($item['tamanho']) ?>
                        </p>

                        <p>
                            Quantidade: <?= (int) $item['qtd_carrinho'] ?>
                        </p>

                        <strong>
                            R$ <?= number_format(
                                $item['preco'] * $item['qtd_carrinho'],
                                2,
                                ',',
                                '.'
                            ) ?>
                        </strong>

                    </div>

                </div>

            <?php endforeach; ?>

            <div class="resumo-total">
                <span>Total</span>

                <span>
                    R$ <?= number_format($total, 2, ',', '.') ?>
                </span>
            </div>

            <button type="submit" class="btn-finalizar">
                Confirmar compra
            </button>

        </aside>

    </form>

<?php endif; ?>
<script>
const opcoesPagamento = document.querySelectorAll(
    'input[name="forma_pagamento"]'
);

const dadosCartao = document.getElementById('dadosCartao');

const camposCartao = [
    document.getElementById('titular'),
    document.getElementById('cartao'),
    document.getElementById('validade'),
    document.getElementById('cvv')
];

function atualizarPagamento() {
    const selecionado = document.querySelector(
        'input[name="forma_pagamento"]:checked'
    );

    const usaCartao = selecionado &&
        ['2', '3'].includes(selecionado.value);

    if (dadosCartao) {
        dadosCartao.style.display = usaCartao ? 'block' : 'none';
    }

    camposCartao.forEach(campo => {
        if (campo) {
            campo.required = !!usaCartao;
        }
    });
}

opcoesPagamento.forEach(opcao => {
    opcao.addEventListener('change', atualizarPagamento);
});

atualizarPagamento();

function copiarPix() {
    const campo = document.getElementById('codigoPix');

    campo.select();
    campo.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(campo.value)
        .then(() => alert('Código PIX copiado!'))
        .catch(() => {
            document.execCommand('copy');
            alert('Código PIX copiado!');
        });
}

const campoCartao = document.getElementById('cartao');

if (campoCartao) {
    campoCartao.addEventListener('input', function () {
        let numero = this.value.replace(/\D/g, '').slice(0, 16);
        this.value = numero.replace(/(\d{4})(?=\d)/g, '$1 ');
    });
}

const campoValidade = document.getElementById('validade');

if (campoValidade) {
    campoValidade.addEventListener('input', function () {
        let valor = this.value.replace(/\D/g, '').slice(0, 4);

        if (valor.length > 2) {
            valor = valor.slice(0, 2) + '/' + valor.slice(2);
        }

        this.value = valor;
    });
}

const campoCvv = document.getElementById('cvv');

if (campoCvv) {
    campoCvv.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 4);
    });
}
</script>
</body>
</html>