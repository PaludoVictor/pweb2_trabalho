# 💰 FinanSys - Sistema de Gestão Financeira

**Trabalho Avaliativo de Programação WEB 2 (PWEB2)**
Continuação temática do projeto **PWEB1** desenvolvido em **Laravel** e **Bootstrap 5**, seguindo 100% o padrão arquitetural do projeto de aula (`pweb2_2026_2`).

---

## 📋 Resumo do Projeto

O **FinanSys** é uma aplicação completa para controle financeiro pessoal e empresarial. Ele permite o gerenciamento de contas bancárias, categorias orçamentárias com tetos de gastos, lançamentos de transações detalhadas com relacionamentos e controle de usuários.

---

## 🛠️ Tecnologias e Recursos

- **Framework**: [Laravel](https://laravel.com/)
- **Linguagem**: PHP 8.x
- **Frontend & Estilos**: [Bootstrap 5.3](https://getbootstrap.com/), FontAwesome 6, Google Fonts (Poppins)
- **Banco de Dados**: MySQL (Laragon) e SQLite
- **Arquitetura**: MVC (Model-View-Controller) com Blade Templates, Eloquent ORM, Migrations, Seeders e Factories

---

## 🎯 Atendimento aos Critérios de Avaliação (10 Pontos)

| Critério                                                   | Status | Detalhes                                                                                                                                                                    |
| :---------------------------------------------------------- | :----: | :-------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **1. Organização do Código (1,0 pt)**              |   ✅   | Estrutura limpa, padronizada e sem erros, idêntica ao repositório de referência`pweb2_2026_2`.                                                                         |
| **2. Layout Profissional (1,0 pt)**                   |   ✅   | Interface moderna, responsiva, com sidebar escura, cards informativos, alertas de feedback (`success`, `error`) e lista de erros de validação.                        |
| **3. Banco de Dados, Migrações e Seeders (1,0 pt)** |   ✅   | 4 migrações de domínio (`contas`, `categorias`, `transacoes`, `usuarios`), com Factories e Seeders para cada tabela. `php artisan migrate --seed` funcional.   |
| **4. CRUDs Completos (4,0 pts)**                      |   ✅   | **4 CRUDs completos** com Salvar, Listar, Buscar, Atualizar e Deletar: Contas Bancárias, Categorias, Transações e Usuários (todos com mais de 3 campos).          |
| **5. Busca em Listagens (Incluso)**                   |   ✅   | Filtro de busca com seleção de campo (`tipo`) e valor de pesquisa (`valor`) em todas as listagens.                                                                    |
| **6. Validação de Dados (0,5 pt)**                  |   ✅   | Validação em todos os formulários via`validateForm(Request $request)` com mensagens amigáveis em português.                                                          |
| **7. Relacionamentos (1,0 pt)**                       |   ✅   | Transação pertence a Conta Bancária (`belongsTo`) e a Categoria (`belongsTo`). Exibição dos nomes e badges nas tabelas e `<select>` dinâmicos nos formulários. |
| **8. Sessão / Autenticação**                       |   ✅   | Autenticação por sessão com login, registro e logout.                                                                                                                    |
| **9. Script SQL da Base**                             |   ✅   | Script completo disponível em`database/db_pweb2_financeiro.sql`.                                                                                                         |

---

## 🚀 Como Executar o Projeto

### 1. Clonar ou Acessar a Pasta

```bash
cd c:\laragon\www\pweb2_trabalho
```

### 2. Instalar Dependências do Composer (se necessário)

```bash
composer install
```

### 3. Configurar o Arquivo `.env` e Chave da Aplicação

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Executar as Migrações e Alimentar a Base (Seeders)

```bash
php artisan migrate:fresh --seed
```

### 5. Iniciar o Servidor de Desenvolvimento

```bash
php artisan serve
```

Acesse no navegador: **`http://127.0.0.1:8000`** ou via Laragon em **`http://pweb2_trabalho.test`**

---

## 🔑 Credenciais Padrão (Seeders)

- **Usuário Administrador**:
  - **Login**: `admin`
  - **Senha**: `123`
- **Usuário Padrão**:
  - **Login**: `joao`
  - **Senha**: `123456`

---

## 📂 Estrutura de Arquivos Principais

```text
app/
├── Http/Controllers/
│   ├── AuthController.php          # Autenticação e Sessão
│   ├── CategoriaController.php     # CRUD Categorias + Busca + Validação
│   ├── ContaController.php         # CRUD Contas Bancárias + Busca + Validação
│   ├── DashboardController.php     # Painel principal e métricas
│   ├── TransacaoController.php     # CRUD Transações + Relacionamentos + Busca
│   └── UsuarioController.php       # CRUD Usuários + Busca + Validação
└── Models/
    ├── Categoria.php               # Model Eloquent Categoria (hasMany Transacoes)
    ├── Conta.php                   # Model Eloquent Conta (hasMany Transacoes)
    ├── Transacao.php               # Model Eloquent Transacao (belongsTo Conta e Categoria)
    └── Usuario.php                 # Model Eloquent Usuario

database/
├── factories/                      # CategoriaFactory, ContaFactory, TransacaoFactory, UsuarioFactory
├── migrations/                     # create_contas, create_categorias, create_transacoes, create_usuarios
├── seeders/                        # CategoriaSeeder, ContaSeeder, TransacaoSeeder, UsuarioSeeder, DatabaseSeeder
└── db_pweb2_financeiro.sql         # Script SQL de entrega

resources/views/
├── auth/                           # login.blade.php, registro.blade.php
├── categoria/                      # form.blade.php, list.blade.php
├── conta/                          # form.blade.php, list.blade.php
├── transacao/                      # form.blade.php, list.blade.php
├── usuario/                        # form.blade.php, list.blade.php
├── dashboard.blade.php             # Painel com gráficos e distribuição
├── main.blade.php                  # Layout mestre Bootstrap 5
└── sidebar.blade.php               # Navegação lateral FinanSys

routes/
└── web.php                         # Definição de todas as rotas da aplicação
```
