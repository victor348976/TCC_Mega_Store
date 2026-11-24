<?php
require_once __DIR__ . '/../geral/auth.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: gerenciar_pedidos.php?erro=1');
    exit;
}

$idVenda = (int) ($_POST['id_venda'] ?? 0);
$status = trim($_POST['status_venda'] ?? 'Pendente');
$statusPermitidos = ['Pendente', 'Pago', 'Enviado', 'Entregue', 'Cancelado'];

if ($idVenda <= 0 || !in_array($status, $statusPermitidos, true)) {
    header('Location: gerenciar_pedidos.php?erro=1');
    exit;
}

$sql = "UPDATE tb_venda SET status_venda = ? WHERE id_venda = ?";
$stmt = mysqli_prepare($con, $sql);
if (!$stmt) {
    header('Location: gerenciar_pedidos.php?erro=1');
    exit;
}
mysqli_stmt_bind_param($stmt, 'si', $status, $idVenda);

if (mysqli_stmt_execute($stmt)) {
    header('Location: gerenciar_pedidos.php?sucesso=1');
    exit;
}

header('Location: gerenciar_pedidos.php?erro=1');
exit;
