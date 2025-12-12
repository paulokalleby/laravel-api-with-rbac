# Laravel API com RBAC (Role-Based Access Control)

Uma API RESTful desenvolvida com Laravel 12, incluindo autenticação via JWT com Laravel Sanctum, autorização baseada em papéis e permissões (RBAC), documentação OpenAPI automática, testes automatizados, e ambiente otimizado para produção utilizando Laravel Octane + Swoole.

## Recursos

-   CRUD completo de Papéis (Roles) e Usuários
-   Autenticação JWT com Laravel Sanctum
-   Recuperação de senha com envio de código de validação por e-mail
-   Autorização baseada em RBAC (Roles & Permissions)
-   Testes automatizados de endpoints com Pest
-   Documentação automática com Scramble (OpenAPI)
-   Desempenho otimizado com Laravel Octane + Swoole
-   Ambiente completo via Docker: Redis, Postgres, Mailhog e Octane

## Tecnologias

-   **Linguagem:** PHP 8.3
-   **Framework:** Laravel 12
-   **Banco de Dados:** PostgreSQL
-   **Cache / Filas:** Redis
-   **Documentação:** Scramble (OpenAPI)
-   **Testes:** Pest
-   **Ambiente:** Docker
-   **Serviços de E-mail (dev):** Mailhog
-   **Servidor de Alta Performance:** Swoole (Octane)

## Como rodar o projeto

#### 1. Clonar o Repositório:

```bash
git clone https://github.com/paulokalleby/laravel-api-with-rbac.git

cd laravel-api-with-rbac
```

#### 2. Configurar Variáveis de Ambiente

```bash
cp .env.example .env
```

#### 3. Gerar a Chave da Aplicação

```bash
docker compose exec app php artisan key:generate
```

## Ambiente de Desenvolvimento

Para iniciar com o ambiente completo (incluindo Mailhog):

```bash
docker compose up -d --build
```

O Mailhog ficará disponível em http://localhost:8025

## Ambiente de Produção (Octane + Swoole)

Para produção, utilize apenas o docker-compose principal:

```bash
docker compose -f docker-compose.yml up -d --build
```

Isso inicia o servidor Laravel Octane com Swoole para alto desempenho.

## Migrations e Seeders

Após subir os containers:

```bash
docker compose exec app php artisan migrate --seed --force
```

### Sincronizar Permissões (Roles & Permissions)

```bash
docker compose exec app php artisan rbac:sync
```

## Documentação da API

A documentação OpenAPI gerada automaticamente está disponível em:
http://localhost:8000/docs/api

## Testes Automatizados com Pest

```bash
php artisan test
```
