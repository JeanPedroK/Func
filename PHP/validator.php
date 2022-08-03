<?php

// function para gerar um loop de coleta de informações da api
function getFromApi($next = ""){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://beta.kunsler.com.br/api/produtos' . $next,
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

    curl_close($curl);
    // codigo padrão retirado do postman
    return json_decode($response, true);
    // parte final da function onde retorna os dados decodificados 
}

// $ = var 
$next = "";
// variavel que atribui vazio a variavel next
$produtos = [];
// atribuiu um array vazio a produtos
echo "Iniciando coleta de paginas...\n";
// echo é um print de string para o terminal 
do {
    // do = fazer, antes de while = validar. a estrutura de repetição
    $res = getFromApi($next);

    $produtos = array_merge($produtos, $res['produtos']);
    // array_merge, junta o array vazio de produtos + o array dinamico obtido pela API pela function getfromapi
    $total = $res['size'];

    $tpro = sizeof($produtos);

    $next = $res['next'];

    echo "$tpro produtos de $total em $next \n";
} while (!empty($next));
// enquanto a var next não estiver vazia ! a estrutura de repetição vai continuar rodando.

$cjson = json_encode($produtos, true);
// json_encode faz o inverso do decode, onde ele organiza o array

$file = 'catalog.json';
// confere a variavel file o nome que vai ser gerado para o arquivo json
file_put_contents($file, $cjson);
// function padrão que seta o local e o conteudo do arquivo.