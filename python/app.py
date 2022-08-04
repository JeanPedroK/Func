import http.client
import json
from ntpath import join

def getApi(next):
    conn = http.client.HTTPSConnection("beta.kunsler.com.br")
    payload = ''
    headers = {
    'Cookie': 'PHPSESSID=ec4909e12372c925458da2259e8218c0'
    }
    conn.request("GET", "/api/produtos"+next, payload, headers)
    res = conn.getresponse()
    data = res.read()
    return json.loads(data.decode("utf-8"))
    


prod = []
next = ""

dados = getApi(next)
prod = prod + dados['produtos']
next = dados['next']
total = dados['size'] 

x = len(prod)

y = 'Iniciando coleta de dados...'

print(y)
print(x, 'de', total, 'produtos') 

while next != "":

    dados = getApi(next)
    prod = prod + dados['produtos']
    next = dados['next']
    total = dados['size'] 
    if next == (bool(0)):
        next = ""

    x = len(prod)
    print(x, 'de', total, 'produtos') 


print("Criando arquivo Json...")

file = open("text.json", "w")
json.dump(prod, file)
file.close()

print("Arquivo criado!")


