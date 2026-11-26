<?php
mysqli_report(MYSQLI_REPORT_OFF);
include "../geral/conexao.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cadastro_produto.php?erro=1');
    exit;
}

$nomeInformado = $_POST['nome'] ?? '';
$descricaoInformada = $_POST['descricao'] ?? '';
$precoRecebido = $_POST['preco'] ?? '';
$nome = is_string($nomeInformado) ? trim($nomeInformado) : '';
$descricao = is_string($descricaoInformada) ? trim($descricaoInformada) : '';
$precoInformado = is_string($precoRecebido) ? str_replace(',', '.', $precoRecebido) : '';
$preco = is_numeric($precoInformado) ? (float) $precoInformado : -1;
$categoria = filter_var($_POST['categoria'] ?? null, FILTER_VALIDATE_INT);
$genero = filter_var($_POST['genero'] ?? null, FILTER_VALIDATE_INT);
$ativo = isset($_POST['ativo']) ? 1 : 0;
$quantidadeCores = filter_var($_POST['quantidade_cores'] ?? null, FILTER_VALIDATE_INT);
$variacoes = $_POST['variacoes'] ?? null;
$dataHoje = date('Y-m-d');

if (
    !$con ||
    $nome === '' ||
    mb_strlen($nome) > 255 ||
    mb_strlen($descricao) > 500 ||
    $preco < 0 ||
    !is_finite($preco) ||
    $categoria === false ||
    $categoria < 1 ||
    $genero === false ||
    $genero < 1 ||
    $genero > 4 ||
    $quantidadeCores === false ||
    $quantidadeCores < 1 ||
    $quantidadeCores > 50 ||
    !is_array($variacoes) ||
    count($variacoes) !== $quantidadeCores
) {
    header('Location: cadastro_produto.php?erro=1');
    exit;
}

$categoriaExiste = mysqli_prepare($con, 'SELECT id_categoria FROM tb_categoria WHERE id_categoria = ?');
if (!$categoriaExiste) {
    header('Location: cadastro_produto.php?erro=1');
    exit;
}
mysqli_stmt_bind_param($categoriaExiste, 'i', $categoria);
mysqli_stmt_execute($categoriaExiste);
$categoriaValida = mysqli_stmt_get_result($categoriaExiste);
if (!$categoriaValida || mysqli_num_rows($categoriaValida) === 0) {
    header('Location: cadastro_produto.php?erro=1');
    exit;
}

$tamanhosPermitidos = ['PP', 'P', 'M', 'G', 'GG', 'XG'];
$coresJaInformadas = [];
$variacoesValidadas = [];
for ($indice = 0; $indice < $quantidadeCores; $indice++) {
    $variacao = $variacoes[$indice] ?? null;
    if (!is_array($variacao) || !isset($variacao['cor']) || !is_string($variacao['cor'])) {
        header('Location: cadastro_produto.php?erro=1');
        exit;
    }

    $cor = trim($variacao['cor']);
    $corNormalizada = mb_strtolower($cor, 'UTF-8');
    $tamanhosSelecionados = $variacao['tamanhos'] ?? null;
    if (
        $cor === '' ||
        mb_strlen($cor) > 55 ||
        isset($coresJaInformadas[$corNormalizada]) ||
        !is_array($tamanhosSelecionados)
    ) {
        header('Location: cadastro_produto.php?erro=1');
        exit;
    }

    $estoques = $variacao['estoque'] ?? null;
    if (!is_array($estoques)) {
        header('Location: cadastro_produto.php?erro=1');
        exit;
    }

    $tamanhosValidos = [];
    foreach ($tamanhosSelecionados as $tamanho) {
        if (!is_string($tamanho) || !in_array(trim($tamanho), $tamanhosPermitidos, true)) {
            continue;
        }

        $tamanho = trim($tamanho);
        $estoque = filter_var($estoques[$tamanho] ?? null, FILTER_VALIDATE_INT);
        if ($estoque === false || $estoque < 0) {
            header('Location: cadastro_produto.php?erro=1');
            exit;
        }
        $tamanhosValidos[$tamanho] = ['tamanho' => $tamanho, 'estoque' => $estoque];
    }
    $tamanhosSelecionados = array_values($tamanhosValidos);
    if (!$tamanhosSelecionados) {
        header('Location: cadastro_produto.php?erro=1');
        exit;
    }

    $coresJaInformadas[$corNormalizada] = true;
    $variacoesValidadas[] = [
        'cor' => $cor,
        'tamanhos' => $tamanhosSelecionados,
    ];
}

$diretorioUploads = __DIR__ . '/../produtos/uploads';
$imagensCriadas = [];
$salvarImagem = static function ($arquivo) use ($diretorioUploads, &$imagensCriadas) {
    if ($arquivo === null || !isset($arquivo['error']) || $arquivo['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    $tiposPermitidos = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];
    if (
        $arquivo['error'] !== UPLOAD_ERR_OK ||
        $arquivo['size'] > 5 * 1024 * 1024 ||
        empty($arquivo['tmp_name']) ||
        !is_uploaded_file($arquivo['tmp_name'])
    ) {
        return false;
    }

    $tipoImagem = mime_content_type($arquivo['tmp_name']);
    if (!isset($tiposPermitidos[$tipoImagem])) {
        return false;
    }
    if (!is_dir($diretorioUploads) && !mkdir($diretorioUploads, 0755, true)) {
        return false;
    }

    $nomeArquivo = bin2hex(random_bytes(16)) . '.' . $tiposPermitidos[$tipoImagem];
    if (!move_uploaded_file($arquivo['tmp_name'], $diretorioUploads . '/' . $nomeArquivo)) {
        return false;
    }

    $caminho = 'uploads/' . $nomeArquivo;
    $imagensCriadas[] = $caminho;
    return $caminho;
};
$removerImagensCriadas = static function () use (&$imagensCriadas, $diretorioUploads) {
    foreach ($imagensCriadas as $caminho) {
        $arquivo = $diretorioUploads . '/' . basename($caminho);
        if (is_file($arquivo)) {
            unlink($arquivo);
        }
    }
};

$imagemPadrao = $salvarImagem($_FILES['imagem'] ?? null);
if ($imagemPadrao === false) {
    header('Location: cadastro_produto.php?erro=1');
    exit;
}

foreach ($variacoesValidadas as $indice => &$variacao) {
    $arquivoImagem = null;
    if (isset($_FILES['variacoes']['error'][$indice]['imagem'])) {
        $arquivoImagem = [
            'name' => $_FILES['variacoes']['name'][$indice]['imagem'] ?? '',
            'type' => $_FILES['variacoes']['type'][$indice]['imagem'] ?? '',
            'tmp_name' => $_FILES['variacoes']['tmp_name'][$indice]['imagem'] ?? '',
            'error' => $_FILES['variacoes']['error'][$indice]['imagem'],
            'size' => $_FILES['variacoes']['size'][$indice]['imagem'] ?? 0,
        ];
    }
    $variacao['imagem'] = $salvarImagem($arquivoImagem);
    if ($variacao['imagem'] === false) {
        $removerImagensCriadas();
        header('Location: cadastro_produto.php?erro=1');
        exit;
    }
    if ($variacao['imagem'] === null) {
        $variacao['imagem'] = $imagemPadrao;
    }
}
unset($variacao);

$sqlProduto = 'INSERT INTO tb_produto (nome_produto, descricao_produto, preco, id_categoria, id_genero, ativo, data_cadastro)
               VALUES (?, ?, ?, ?, ?, ?, ?)';
$stmtProduto = mysqli_prepare($con, $sqlProduto);
$stmtVariacao = mysqli_prepare($con, 'INSERT INTO tb_variacao_produto (id_produto, tamanho, cor, estoque) VALUES (?, ?, ?, ?)');
$stmtImagem = mysqli_prepare($con, 'INSERT INTO tb_imagem_produto (id_variacao, caminho_imagem) VALUES (?, ?)');

if (!$stmtProduto || !$stmtVariacao || !$stmtImagem) {
    $removerImagensCriadas();
    header('Location: cadastro_produto.php?erro=1');
    exit;
}

if (!$con->begin_transaction()) {
    $removerImagensCriadas();
    header('Location: cadastro_produto.php?erro=1');
    exit;
}

$salvouProduto = mysqli_stmt_bind_param($stmtProduto, 'ssdiiis', $nome, $descricao, $preco, $categoria, $genero, $ativo, $dataHoje)
    && mysqli_stmt_execute($stmtProduto);
$idProduto = $salvouProduto ? mysqli_insert_id($con) : 0;
$salvouVariacoes = $salvouProduto;

foreach ($variacoesValidadas as $variacao) {
    foreach ($variacao['tamanhos'] as $itemTamanho) {
        $cor = $variacao['cor'];
        $tamanho = $itemTamanho['tamanho'];
        $estoque = $itemTamanho['estoque'];
        mysqli_stmt_bind_param($stmtVariacao, 'issi', $idProduto, $tamanho, $cor, $estoque);
        if (!mysqli_stmt_execute($stmtVariacao)) {
            $salvouVariacoes = false;
            break 2;
        }

        if ($variacao['imagem'] !== null) {
            $idVariacao = mysqli_insert_id($con);
            mysqli_stmt_bind_param($stmtImagem, 'is', $idVariacao, $variacao['imagem']);
            if (!mysqli_stmt_execute($stmtImagem)) {
                $salvouVariacoes = false;
                break 2;
            }
        }
    }
}

if ($salvouVariacoes && $con->commit()) {
    header('Location: cadastro_produto.php?sucesso=1');
    exit;
}

$con->rollback();
$removerImagensCriadas();
header('Location: cadastro_produto.php?erro=1');
exit;
