
<?php
include("../geral/conexao.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/login.php");
    exit;
}

$id_usuario = (int) $_SESSION['id_usuario'];
$mensagem = "";
$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'atualizar_usuario') {
        $nome = trim($_POST['nome'] ?? '');
        $cpf = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
        $telefone = preg_replace('/\D/', '', $_POST['telefone'] ?? '');

        if ($nome === '' || strlen($cpf) !== 11 || !in_array(strlen($telefone), [10, 11])) {
            $erro = "Confira os dados informados.";
        } else {
            $sql = "UPDATE tb_usuario
                    SET nome_usuario = ?, cpf = ?, numero = ?
                    WHERE id_usuario = ?";

            $stmt = mysqli_prepare($con, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sssi", $nome, $cpf, $telefone, $id_usuario);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $mensagem = "Informações atualizadas com sucesso!";
            } else {
                $erro = "Não foi possível atualizar seus dados.";
            }
        }
    }

    if ($acao === 'adicionar_endereco' || $acao === 'editar_endereco') {
        $rua = trim($_POST['rua'] ?? '');
        $numero = (int) ($_POST['numero'] ?? 0);
        $bairro = trim($_POST['bairro'] ?? '');
        $cidade = trim($_POST['cidade'] ?? '');
        $estado = strtoupper(trim($_POST['estado'] ?? ''));
        $cep = preg_replace('/\D/', '', $_POST['cep'] ?? '');
        $complemento = trim($_POST['complemento'] ?? '');

        if (
            $rua === '' ||
            $numero <= 0 ||
            $bairro === '' ||
            $cidade === '' ||
            strlen($estado) !== 2 ||
            strlen($cep) !== 8
        ) {
            $erro = "Confira os dados do endereço.";
        } else {
            if ($acao === 'adicionar_endereco') {
                $sql = "INSERT INTO tb_endereco
                        (id_usuario, rua, numero, bairro, cidade, estado, cep, complemento)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = mysqli_prepare($con, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param(
                        $stmt,
                        "isisssss",
                        $id_usuario,
                        $rua,
                        $numero,
                        $bairro,
                        $cidade,
                        $estado,
                        $cep,
                        $complemento
                    );

                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    $mensagem = "Endereço cadastrado com sucesso!";
                } else {
                    $erro = "Não foi possível cadastrar o endereço.";
                }
            } else {
                $id_endereco = (int) ($_POST['id_endereco'] ?? 0);

                $sql = "UPDATE tb_endereco
                        SET rua = ?, numero = ?, bairro = ?, cidade = ?,
                            estado = ?, cep = ?, complemento = ?
                        WHERE id_endereco = ? AND id_usuario = ?";

                $stmt = mysqli_prepare($con, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param(
                        $stmt,
                        "sisssssii",
                        $rua,
                        $numero,
                        $bairro,
                        $cidade,
                        $estado,
                        $cep,
                        $complemento,
                        $id_endereco,
                        $id_usuario
                    );

                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    $mensagem = "Endereço atualizado com sucesso!";
                } else {
                    $erro = "Não foi possível atualizar o endereço.";
                }
            }
        }
    }
}

/* Dados do usuário */
$sql = "SELECT nome_usuario, email, cpf, numero
        FROM tb_usuario
        WHERE id_usuario = ?";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$usuario = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

/* Endereços */
$sql = "SELECT *
        FROM tb_endereco
        WHERE id_usuario = ?
        ORDER BY id_endereco DESC";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$enderecos = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

/* Pedidos recentes */
$sql = "SELECT
            v.id_venda,
            v.data_pedido,
            v.status_venda,
            v.valor_total,
            pv.quantidade,
            pv.preco_unitario,
            p.nome_produto,
            vp.cor,
            vp.tamanho
        FROM tb_venda v
        LEFT JOIN tb_produto_venda pv
            ON pv.id_venda = v.id_venda
        LEFT JOIN tb_variacao_produto vp
            ON vp.id_variacao = pv.id_variacao
        LEFT JOIN tb_produto p
            ON p.id_produto = vp.id_produto
        WHERE v.id_usuario = ?
        ORDER BY v.id_venda DESC
        LIMIT 50";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$resultadoPedidos = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

$pedidos = [];

foreach ($resultadoPedidos as $pedido) {
    $id = $pedido['id_venda'];

    if (!isset($pedidos[$id])) {
        $pedidos[$id] = [
            'id_venda' => $pedido['id_venda'],
            'data_pedido' => $pedido['data_pedido'],
            'status_venda' => $pedido['status_venda'],
            'valor_total' => $pedido['valor_total'],
            'produtos' => []
        ];
    }

    if ($pedido['nome_produto'] !== null) {
        $pedidos[$id]['produtos'][] = [
            'nome' => $pedido['nome_produto'],
            'cor' => $pedido['cor'],
            'tamanho' => $pedido['tamanho'],
            'quantidade' => $pedido['quantidade'],
            'preco' => $pedido['preco_unitario']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha conta | Mega Store</title>
    <link rel="stylesheet" href="perfil.css">
</head>
<body>

<div class="perfil-container">

    <aside class="sidebar">
        <h2>Minha conta</h2>

        <nav>
            <button class="nav-item ativo" onclick="mostrarSecao('informacoes', this)">
                Informações
            </button>

            <button class="nav-item" onclick="mostrarSecao('enderecos', this)">
                Endereços
            </button>

            <button class="nav-item" onclick="mostrarSecao('pedidos', this)">
                Pedidos recentes
            </button>
        </nav>

        <a href="../index.php" class="voltar">Voltar à loja</a>
    </aside>

    <main class="conteudo">

        <?php if ($mensagem !== ''): ?>
            <div class="mensagem sucesso">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <?php if ($erro !== ''): ?>
            <div class="mensagem erro">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <!-- INFORMAÇÕES -->
        <section id="informacoes" class="secao ativa">

            <div class="cabecalho">
                <h1>Informações pessoais</h1>
                <p>Gerencie os dados da sua conta.</p>
            </div>

            <form method="POST" class="card">
                <input type="hidden" name="acao" value="atualizar_usuario">

                <div class="campo">
                    <label>Nome completo</label>
                    <input type="text" name="nome"
                        value="<?= htmlspecialchars($usuario['nome_usuario'] ?? '') ?>"
                        required>
                </div>

                <div class="campo">
                    <label>E-mail</label>
                    <input type="email"
                        value="<?= htmlspecialchars($usuario['email'] ?? '') ?>"
                        disabled>
                    <small>O e-mail não pode ser alterado por esta página.</small>
                </div>

                <div class="campo">
                    <label>CPF</label>
                    <input type="text" name="cpf"
                        value="<?= htmlspecialchars($usuario['cpf'] ?? '') ?>"
                        maxlength="11" placeholder="Somente números" required>
                </div>

                <div class="campo">
                    <label>Telefone</label>
                    <input type="text" name="telefone"
                        value="<?= htmlspecialchars($usuario['numero'] ?? '') ?>"
                        maxlength="11" placeholder="Somente números" required>
                </div>

                <button type="submit" class="btn">Salvar alterações</button>
            </form>
        </section>

        <!-- ENDEREÇOS -->
        <section id="enderecos" class="secao">

            <div class="cabecalho">
                <h1>Meus endereços</h1>
                <p>Visualize e atualize seus endereços de entrega.</p>
            </div>

            <?php foreach ($enderecos as $endereco): ?>
                <form method="POST" class="card endereco-card">
                    <input type="hidden" name="acao" value="editar_endereco">
                    <input type="hidden" name="id_endereco"
                        value="<?= (int) $endereco['id_endereco'] ?>">

                    <h3>Endereço #<?= (int) $endereco['id_endereco'] ?></h3>

                    <div class="form-grid">
                        <div class="campo completo">
                            <label>Rua</label>
                            <input type="text" name="rua"
                                value="<?= htmlspecialchars($endereco['rua']) ?>" required>
                        </div>

                        <div class="campo">
                            <label>Número</label>
                            <input type="number" name="numero"
                                value="<?= htmlspecialchars((string) $endereco['numero']) ?>"
                                min="1" required>
                        </div>

                        <div class="campo">
                            <label>Bairro</label>
                            <input type="text" name="bairro"
                                value="<?= htmlspecialchars($endereco['bairro']) ?>" required>
                        </div>

                        <div class="campo">
                            <label>Cidade</label>
                            <input type="text" name="cidade"
                                value="<?= htmlspecialchars($endereco['cidade']) ?>" required>
                        </div>

                        <div class="campo">
                            <label>Estado</label>
                            <input type="text" name="estado"
                                value="<?= htmlspecialchars($endereco['estado']) ?>"
                                maxlength="2" required>
                        </div>

                        <div class="campo">
                            <label>CEP</label>
                            <input type="text" name="cep"
                                value="<?= htmlspecialchars($endereco['cep']) ?>"
                                maxlength="8" required>
                        </div>

                        <div class="campo">
                            <label>Complemento</label>
                            <input type="text" name="complemento"
                                value="<?= htmlspecialchars($endereco['complemento'] ?? '') ?>">
                        </div>
                    </div>

                    <button type="submit" class="btn">Salvar endereço</button>
                </form>
            <?php endforeach; ?>

            <form method="POST" class="card">
                <input type="hidden" name="acao" value="adicionar_endereco">

                <h3>Adicionar novo endereço</h3>

                <div class="form-grid">
                    <div class="campo completo">
                        <label>Rua</label>
                        <input type="text" name="rua" required>
                    </div>

                    <div class="campo">
                        <label>Número</label>
                        <input type="number" name="numero" min="1" required>
                    </div>

                    <div class="campo">
                        <label>Bairro</label>
                        <input type="text" name="bairro" required>
                    </div>

                    <div class="campo">
                        <label>Cidade</label>
                        <input type="text" name="cidade" required>
                    </div>

                    <div class="campo">
                        <label>Estado</label>
                        <input type="text" name="estado" maxlength="2" required>
                    </div>

                    <div class="campo">
                        <label>CEP</label>
                        <input type="text" name="cep" maxlength="8" required>
                    </div>

                    <div class="campo">
                        <label>Complemento</label>
                        <input type="text" name="complemento">
                    </div>
                </div>

                <button type="submit" class="btn">Cadastrar endereço</button>
            </form>

        </section>

        <!-- PEDIDOS -->
        <section id="pedidos" class="secao">

            <div class="cabecalho">
                <h1>Pedidos recentes</h1>
                <p>Acompanhe suas compras realizadas na Mega Store.</p>
            </div>

            <?php if (empty($pedidos)): ?>
                <div class="card">
                    <p>Você ainda não realizou nenhuma compra.</p>
                </div>
            <?php else: ?>

                <?php foreach ($pedidos as $pedido): ?>
                    <div class="card pedido">

                        <div class="pedido-topo">
                            <div>
                                <h3>Pedido #<?= (int) $pedido['id_venda'] ?></h3>
                                <small>
                                    <?= date('d/m/Y', strtotime($pedido['data_pedido'])) ?>
                                </small>
                            </div>

                            <span class="status">
                                <?= htmlspecialchars($pedido['status_venda']) ?>
                            </span>
                        </div>

                        <?php foreach ($pedido['produtos'] as $produto): ?>
                            <div class="pedido-produto">
                                <div>
                                    <strong><?= htmlspecialchars($produto['nome']) ?></strong>
                                    <p>
                                        Cor: <?= htmlspecialchars($produto['cor']) ?> |
                                        Tamanho: <?= htmlspecialchars($produto['tamanho']) ?>
                                    </p>
                                    <p>Quantidade: <?= (int) $produto['quantidade'] ?></p>
                                </div>

                                <span>
                                    R$ <?= number_format(
                                        $produto['preco'] * $produto['quantidade'],
                                        2, ',', '.'
                                    ) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>

                        <div class="pedido-total">
                            Total: R$ <?= number_format($pedido['valor_total'], 2, ',', '.') ?>
                        </div>

                    </div>
                <?php endforeach; ?>

            <?php endif; ?>

        </section>

    </main>
</div>

<script>
function mostrarSecao(id, botao) {
    document.querySelectorAll('.secao').forEach(secao => {
        secao.classList.remove('ativa');
    });

    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('ativo');
    });

    document.getElementById(id).classList.add('ativa');
    botao.classList.add('ativo');
}
</script>

</body>
</html>
