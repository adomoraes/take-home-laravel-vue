## 🚀 Instalação e Execução (Backend)

Este projeto utiliza [Laravel Sail](https://laravel.com/docs/sail), que fornece um ambiente de desenvolvimento local completo baseado em Docker.

### Pré-requisitos

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/) (geralmente incluído no Docker Desktop)

### Passos para Instalação

1.  **Clone o repositório e entre no diretório do Laravel:**

    ```bash
    git clone https://github.com/eyecarehealth/take-home-laravel-vue
    cd take-home-laravel-vue/laravel
    ```

2.  **Instale as dependências do Composer:**

    > O Sail utilizará a imagem Docker do PHP para executar o Composer. Você não precisa ter o PHP ou o Composer instalados localmente.

    ```bash
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs
    ```

3.  **Copie o arquivo de ambiente:**

    ```bash
    cp .env.example .env
    ```

4.  **Inicie os containers do Sail em modo detached:**

    ```bash
    ./vendor/bin/sail up -d
    ```

5.  **Gere a chave da aplicação:**

    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

6.  **Execute as migrações e os seeders:**
    > Isso criará a estrutura do banco de dados e populará as tabelas com dados de exemplo para facilitar os testes.
    ```bash
    ./vendor/bin/sail artisan migrate --seed
    ```

Pronto! A aplicação backend estará em execução e acessível em `http://localhost`.

---

## 🧪 Testando a API

A API pode ser testada utilizando a coleção do Postman incluída no projeto ou executando a suíte de testes automatizados.

### 1. Usando a Coleção do Postman

1.  Na raiz do subdiretório `laravel`, você encontrará o arquivo `Exames_API.postman_collection.json`.
2.  Importe este arquivo no seu cliente Postman.
3.  A coleção já contém as requisições para todos os endpoints da API, com exemplos de corpo (body) para as requisições `POST` e `PUT`.

#### Principais Endpoints

- `GET /api/exames`: Lista todos os exames.
- `POST /api/exames`: Cria um novo exame.
- `GET /api/pacotes`: Lista todos os pacotes com seus exames.
- `POST /api/pacotes`: Cria um novo pacote com exames associados.
- `POST /api/pacotes/{id}/exames`: Adiciona novos exames a um pacote existente.
- `POST /api/gerar-impressao`: Gera o PDF da solicitação.
  - **Exemplo de corpo (body):**
    ```json
    {
    	"exames": [1, 2],
    	"pacotes": [3]
    }
    ```

### 2. Executando os Testes Automatizados (PHPUnit)

Para rodar a suíte de testes automatizados do Laravel, que valida as regras de negócio e o funcionamento dos endpoints, execute o seguinte comando:

```bash
./vendor/bin/sail artisan test
```

<p align="center" id="top">
    <img alt="Readme" title="Readme GIF" src="./assets/tests-unit-laravel.png" />
</p>

## ✨ Frontend (Vue.js 2)

A interface do sistema foi construída com Vue.js 2, proporcionando uma experiência de usuário reativa e amigável para gerenciar exames e pacotes.

### Dashboard

A página inicial apresenta os principais módulos do sistema: Solicitação de Exames, Gestão de Exames e Gestão de Pacotes.

<p align="center" id="top">
    <img alt="Dashboard" title="Dashboard" src="./assets/desktop-dashboard.png" />
</p>

### Gestão de Exames

Nesta tela, o usuário pode visualizar, criar, editar e remover os exames que serão utilizados nas solicitações e pacotes.

<p align="center" id="top">
    <img alt="Gestão de Exames" title="Gestão de Exames" src="./assets/desktop-gestao_exame_lista.png" />
</p>

O formulário para criar ou editar um exame é apresentado em um modal, simplificando o fluxo de trabalho.

<p align="center" id="top">
    <img alt="Modal de Gestão de Exame" title="Modal de Gestão de Exame" src="./assets/desktop-gestao_exame_modal.png" />
</p>

### Gestão de Pacotes

Esta seção permite o gerenciamento de pacotes, que são agrupamentos de exames para agilizar a solicitação.

<p align="center" id="top">
    <img alt="Gestão de Pacotes" title="Gestão de Pacotes" src="./assets/desktop-gestao-pacotes.png" />
</p>

Assim como na gestão de exames, um modal é utilizado para criar e editar os pacotes, permitindo associar os exames desejados.

<p align="center" id="top">
    <img alt="Modal de Gestão de Pacotes" title="Modal de Gestão de Pacotes" src="./assets/desktop-gestao_pacotes-modal.png" />
</p>

### Solicitação de Exames

A tela principal da aplicação, onde o médico pode solicitar exames de forma avulsa ou através de pacotes pré-definidos, e então gerar a impressão.

<p align="center" id="top">
    <img alt="Solicitação de Exames" title="Solicitação de Exames" src="./assets/desktop-gestao-solicitacao.png" />
</p>

### Telas Mobile

O sistema é totalmente responsivo, garantindo uma experiência de uso consistente em dispositivos móveis.

**Navegação e Dashboard Mobile**

A navegação mobile é intuitiva, com um menu inferior que dá acesso rápido às principais seções. O dashboard se adapta ao formato de tela menor, mantendo a clareza das informações.

<p align="center" id="top">
    <img alt="Navbar Mobile" title="Navbar Mobile" src="./assets/mobile-navbar-active.png" width="300"/>
    <img alt="Dashboard Mobile" title="Dashboard Mobile" src="./assets/mobile-dashboard.png" width="300"/>
</p>

**Gestão de Exames e Pacotes Mobile**

As telas de gestão de exames e pacotes são otimizadas para toque, facilitando a visualização e manipulação dos dados.

<p align="center" id="top">
    <img alt="Gestão de Exames Mobile" title="Gestão de Exames Mobile" src="./assets/mobile-gestao_exames-lista.png" width="300"/>
    <img alt="Modal de Exame Mobile" title="Modal de Exame Mobile" src="./assets/mobile-gestao_exames-modal.png" width="300"/>
</p>

<p align="center" id="top">
    <img alt="Gestão de Pacotes Mobile" title="Gestão de Pacotes Mobile" src="./assets/mobile-gestao_pacotes-lista.png" width="300"/>
    <img alt="Modal de Pacote Mobile" title="Modal de Pacote Mobile" src="./assets/mobile-gestao_pacotes-modal.png" width="300"/>
</p>

**Solicitação de Exames Mobile**

O processo de solicitação de exames também foi adaptado para dispositivos móveis, permitindo que o médico adicione exames avulsos ou pacotes de forma rápida.

<p align="center" id="top">
    <img alt="Solicitação Mobile" title="Solicitação Mobile" src="./assets/mobile-solicitacao.png" width="300"/>
    <img alt="Solicitação Avulso Mobile" title="Solicitação Avulso Mobile" src="./assets/mobile-solicitacao-avulso.png" width="300"/>
</p>

<p align="center" id="top">
    <img alt="Solicitação Pacotes Mobile" title="Solicitação Pacotes Mobile" src="./assets/mobile-solicitacao-pacotes.png" width="300"/>
</p>

---
