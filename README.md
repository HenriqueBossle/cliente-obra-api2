# 🏗️ Cliente Obra API

API REST desenvolvida em **Laravel** para gerenciamento de **usuários** e **obras** (cadastro de obras com dados do construtor, gestor de obra, volumes de concreto/argamassa, datas, status e geração de relatórios em PDF).

A autenticação é feita com **Laravel Sanctum** (tokens Bearer), com **verificação de e-mail** obrigatória antes do login e **recuperação de senha via código OTP** enviado por e-mail (Brevo).

---

## 🚀 Tecnologias

| Tecnologia | Badge | Uso |
|---|---|---|
| Laravel 13 | ![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white) | Framework PHP |
| PHP 8.3 | ![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white) | Linguagem |
| Laravel Sanctum | ![Sanctum](https://img.shields.io/badge/Sanctum-4.0-FF2D20?logo=laravel&logoColor=white) | Autenticação via API tokens |
| PostgreSQL / SQLite | ![PostgreSQL](https://img.shields.io/badge/PostgreSQL-4169E1?logo=postgresql&logoColor=white) ![SQLite](https://img.shields.io/badge/SQLite-003B57?logo=sqlite&logoColor=white) | Banco de dados |
| laravel-dompdf | ![DomPDF](https://img.shields.io/badge/DomPDF-3.1-0F766E) | Geração de PDFs |
| Brevo | ![Brevo](https://img.shields.io/badge/Brevo-0B996E?logo=brevo&logoColor=white) | E-mails transacionais (verificação e OTP) |
| Eloquent ORM | ![Eloquent](https://img.shields.io/badge/Eloquent-ORM-FF2D20?logo=laravel&logoColor=white) | Camada de dados |
| Docker | ![Docker](https://img.shields.io/badge/Docker-2496ED?logo=docker&logoColor=white) | Containerização |

---

## ✨ Funcionalidades

### 🔐 Autenticação e usuários

- Cadastro de usuário com **envio de e-mail de verificação** (link assinado válido por 60 min)
- Reenvio de e-mail de verificação
- Login (exige e-mail verificado) gerando **token Sanctum** por dispositivo (`device_name`)
- Logout (revoga o token atual)
- Visualização e atualização do perfil
- Alteração de senha
- Exclusão de conta (confirma com a senha atual e revoga todos os tokens)
- **Recuperação de senha por OTP**: código de 6 dígitos enviado por e-mail, válido por 15 minutos

### 🏗️ Obras (Constructions)

- Criar, listar, visualizar, atualizar e excluir obras
- Cada usuário acessa **apenas suas próprias obras** (autorização via Policy)
- **Busca textual** por nome da obra, construtor, CPF/CNPJ, gestor, endereço, tipo e status
- **Geração de PDF**: relatório de uma obra ou de todas as obras do usuário

### 💬 Feedback

- Envio de feedback/sugestão vinculado ao usuário autenticado

### 🛡️ Segurança

- Rate limiting: 8 req/min em login/registro, 60 req/min nas rotas autenticadas, 5 req/min nas rotas de OTP
- Respostas genéricas na recuperação de senha (não revela se o e-mail existe)
- Senhas com `bcrypt`, hash de OTP no banco, expiração de códigos
