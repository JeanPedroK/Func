package main

import (
	"encoding/json"
	"fmt"
	"io/ioutil"
	"log"
	"net/http"
	"os"
)

type Produto struct {
	Codigo          string   `json:"codigo"`
	CodSubcategoria string   `json:"cod_subcategoria"`
	Ativo           string   `json:"ativo"`
	Destaque        string   `json:"destaque"`
	Referencia      string   `json:"referencia"`
	Titulo          string   `json:"titulo"`
	ValorDe         string   `json:"valor_de"`
	ValorPor        string   `json:"valor_por"`
	Peso            string   `json:"peso"`
	Altura          string   `json:"altura"`
	Largura         string   `json:"largura"`
	Comprimento     string   `json:"comprimento"`
	Descricao       string   `json:"descricao"`
	PalavrasChaves  string   `json:"palavras_chaves"`
	ValorAtacado    string   `json:"valor_atacado"`
	DataCadastro    string   `json:"data_cadastro"`
	Medida          string   `json:"medida"`
	Slides          []string `json:"slides"`
}

type ApiData struct {
	Produtos []Produto `json:"produtos"`
	Size     string    `json:"size"`
	Next     string    `json:"next"`
}

func getApi(next string) ApiData {

	var dados ApiData

	url := "https://beta.kunsler.com.br/api/produtos" + next
	method := "GET"

	client := &http.Client{}
	req, err := http.NewRequest(method, url, nil)

	if err != nil {
		fmt.Println(err)
		return dados
	}
	res, err := client.Do(req)
	if err != nil {
		fmt.Println(err)
		return dados
	}
	defer res.Body.Close()

	body, err := ioutil.ReadAll(res.Body)
	if err != nil {
		fmt.Println(err)
		return dados
	}

	// fmt.Println(string(body))

	json.Unmarshal(body, &dados)

	return dados
}

func main() {

	log.Println("Iniciando coleta de dados...")

	var prod []Produto

	next := ""

	for {

		data := getApi(next)

		next = data.Next

		prod = append(prod, data.Produtos...)

		fmt.Println("Mostrando", len(prod), "Produtos de", data.Size)

		if next == "" {
			break
		}
	}

	b, err := json.Marshal(prod)

	if err != nil {
		log.Fatal(err)
	}

	SaveFile("prod.json", b)

}

func SaveFile(filename string, data []byte) {

	f, err := os.Create(filename)

	if err != nil {
		log.Fatal(err)
	}

	defer f.Close()

	_, err2 := f.WriteString(string(data))

	if err2 != nil {
		log.Fatal(err2)
	}

	fmt.Println("Done file saved")

}
