<?php

echo "Iniciando coleta...\n";

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://beta.kunsler.com.br/api/produtos/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Cookie: PHPSESSID=1a0d0270fa07a5dc25037cdfa4558867'
    ),
));

$response = curl_exec($curl);


$file = 'produtos.json';
file_put_contents($file, $response);
echo "Criando arquivo... $file \n";

curl_close($curl);

echo "Decodificando Json...\n";
$json = $response;
$list = json_decode($json, true);

$prod = sizeof($list['produtos']);

echo "Total: " . $list['size'] . " Produtos: $prod \n";
