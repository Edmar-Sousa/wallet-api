### Requisitos

[] dois tipos de usaario no sistema
 - [] comuns
 - [] lojistas

> ambos tem uma carteira e realizam transferencias

1. [x] Os usuarios devem ter
 - [x] Nome
 - [x] cpf/cnpj (unico)
 - [x] email (unico)
 - [x] senha

2. [] Usuários podem enviar dinheiro (efetuar transferência) para lojistas e entre usuários
3. [] Lojistas só recebem transferências
4. [] Validar se o usuário tem saldo antes da transferência
5. [] Deve-se consultar um serviço autorizador externo, use este mock https://util.devi.tools/api/v2/authorize para simular o serviço utilizando o verbo GET
6. [] A operação de transferência deve ser uma transação 
7. [] No recebimento de pagamento, o usuário ou lojista precisa receber notificação (Use este mock `POST` https://util.devi.tools/api/v1/notify)