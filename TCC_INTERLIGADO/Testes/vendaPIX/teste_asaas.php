
<?php

include("asaas.php");

$url = ASAAS_API_URL . "/myAccount";

$curl = curl_init($url);

curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'User-Agent: MegaStore',
        'access_token: ' . ASAAS_API_KEY
    ]
]);

$resposta = curl_exec($curl);
$codigo = curl_getinfo($curl, CURLINFO_HTTP_CODE);

if ($resposta === false) {
    echo "Erro de conexão: " . curl_error($curl);
} else {
    $dados = json_decode($resposta, true);

    if ($codigo == 200) {
        echo "Conexão realizada com sucesso!<br>";
        echo "API do Asaas funcionando.";
    } else {
        echo "Erro na comunicação. Código HTTP: " . $codigo . "<br>";

        foreach ($dados['errors'] ?? [] as $erro) {
            echo htmlspecialchars($erro['description']) . "<br>";
        }
    }
}

curl_close($curl);

?>
