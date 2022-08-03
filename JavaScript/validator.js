var https = require('follow-redirects').https;
var fs = require('fs');

function getData(next = '') {

    return new Promise((response, erro) => {


        var options = {
            'method': 'GET',
            'hostname': 'beta.kunsler.com.br',
            'path': '/api/produtos' + `${next}`,
            'headers': {
            },
            'maxRedirects': 20
        };

        var req = https.request(options, function (res) {
            var chunks = [];

            res.on("data", function (chunk) {
                chunks.push(chunk);
            });

            res.on("end", function (chunk) {
                var body = Buffer.concat(chunks);
                response(body.toString())
            });

            res.on("error", function (error) {
                console.error(error);
                erro(error)
            });

        });

        req.end();

    })
}
console.log("Iniciando coleta de dados...");


(async () => {




    var next = '';

    var merc = [];

    do {

        var data = await getData(next);

        var list = JSON.parse(data);

        var size = list.size;

        var prod = list.produtos.length;

        merc = merc.concat(list.produtos);

        var tmerc = merc.length;

        console.log(` Mostrando ${tmerc} de um total de ${size}`);

        next = list.next

    } while (next != '');


    var file = "./jsonconf.json";

    var text = JSON.stringify(merc, null, 2);

    fs.writeFileSync(file, text);

})()



