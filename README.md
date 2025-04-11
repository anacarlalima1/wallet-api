# Wallet API

## Tecnologias Usadas
- **PHP**: Framework para backend.
- **Laravel 8**: Framework MVC utilizado para construção da API.
- **MySQL**: Banco de dados relacional para persistência dos dados.
- **Redis**: Para cache e gerenciamento de fila.
- **Docker**: Ambiente de containerização para facilitar o desenvolvimento e execução.
- **Postman**: Ferramenta para testar os endpoints da API.
- **PHPUnit**: Para execução de testes automatizados.

---

## Requisitos
Para rodar o projeto, é necessário ter:
- **PHP 7.4 ou superior**
- **Composer**: Gerenciador de dependências PHP
- **Docker e Docker Compose** (opcional para ambiente isolado)

---

## Estrutura do Projeto
O projeto segue a arquitetura Monolito Modular e utiliza práticas de **DDD (Domain-Driven Design)**, com os seguintes módulos:

- **Domains**: Contém as entidades de domínio como `User`, `Transaction`, etc.
- **Application**: Casos de uso que orquestram a lógica de negócios, como o caso de uso `TransferMoneyUseCase`.
- **Infrastructure**: Implementações de infraestruturas, como repositórios e serviços externos (API de autorização e notificação).
- **App**: Contém os controladores, rotas e outras funcionalidades gerais do Laravel.
- **Tests**: Contém os testes unitários e de integração do projeto.

---

## Configuração

1. Clone o repositório:
    ```bash
    git clone https://github.com/seu-usuario/wallet-api.git
    cd wallet-api
    ```

2. Instale as dependências do Composer:
    ```bash
    composer install
    ```

3. Copie o arquivo `.env.example` para `.env`:
    ```bash
    cp .env.example .env
    ```

4. Gere a chave de aplicação do Laravel:
    ```bash
    php artisan key:generate
    ```

5. Configure as variáveis no `.env`, como `REDIS_HOST`, `DB_CONNECTION`, `MAIL_MAILER` e outras conforme necessário.

6. Se estiver usando Docker, inicie os contêineres:
    ```bash
    docker-compose up -d
    ```

7. Execute as migrações:
    ```bash
    php artisan migrate
    ```

8. Popule o banco de dados com dados iniciais, se necessário:
    ```bash
    php artisan db:seed
    ```

---

## Execução

Após configurar o ambiente e rodar as migrações, você pode acessar a API. Por padrão, a API está rodando no `http://localhost:8080` (se estiver usando Docker, o Nginx estará exposto na porta 8080).

### Endpoints Principais
- **POST /transfer**: Realiza a transferência de dinheiro entre usuários.

#### Exemplo de Requisição:
```json
POST /transfer
{
  "payer": 1,
  "payee": 2,
  "value": 50.00
}
```
##  Resposta de Sucesso

```json
{
  "message": "Transferência realizada com sucesso.",
  "payer_balance": 50.00,
  "payee_balance": 150.00
}
```
##  Exemplo de Resposta de Erro

```json
{
  "error": "Saldo insuficiente."
}
```
##  Testes

O projeto inclui testes unitários e de integração para verificar a lógica de transferência de dinheiro e outras funcionalidades.

###  Rodando Testes Unitários e de Integração

Se estiver usando Docker, entre no container e rode:

```bash
php artisan test
```
##  Considerações Finais

Este projeto tem como objetivo realizar a **transferência de dinheiro entre usuários**, utilizando a **arquitetura DDD**, boas práticas de **testabilidade** e **segurança**.
As funcionalidades podem ser testadas diretamente via **Postman**, utilizando os endpoints documentados.
