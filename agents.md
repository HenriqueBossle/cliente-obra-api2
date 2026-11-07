# Orientações para agentes

## Contexto do projeto

ClienteObra API é um backend Laravel em PHP 8.3 ou superior. A aplicação expõe endpoints JSON para autenticação, gerenciamento de obras, perfil e feedback. Usa Laravel Sanctum para tokens, Eloquent e migrations para persistência, e DomPDF para relatórios de obras. O projeto também integra envio de e-mail por meio do Brevo.

## Organização principal

- `routes/api.php`: rotas da API e seus middlewares.
- `app/Http/Controllers/Api/`: controladores dos endpoints.
- `app/Http/Requests/`: validação de entrada.
- `app/Http/Resources/`: representação dos recursos JSON.
- `app/Models/` e `app/Policies/`: modelos e regras de acesso.
- `app/Services/`: integrações externas, incluindo e-mail.
- `database/migrations/`: alterações versionadas no esquema.
- `resources/views/pdf/`: templates dos relatórios PDF.
- `tests/Feature/` e `tests/Unit/`: testes automatizados.

## Regras de trabalho

1. Leia as rotas, o controlador, as requests, os recursos, as policies e os testes relacionados antes de alterar um fluxo.
2. Preserve os contratos existentes da API. Se uma mudança afetar payloads, códigos HTTP ou autenticação, atualize os testes e a documentação correspondente.
3. Use Form Requests para validação de endpoints e mantenha autorização alinhada entre policies e consultas: cada usuário só pode acessar as próprias obras.
4. Ao adicionar ou modificar campos persistidos, use migrations; não altere diretamente bancos locais ou dados gerados.
5. Não inclua credenciais, tokens ou valores de `.env` em código, documentação, logs ou exemplos. Use `.env.example` apenas para nomes de variáveis sem segredos.
6. Siga os padrões Laravel e PSR-4 existentes. Evite dependências novas quando os recursos instalados forem suficientes.
7. Para integrações externas, mantenha chamadas isoladas em serviços e use fakes/mocks nos testes, sem depender de serviços reais.
8. Prefira testes de Feature para comportamento HTTP e autorização; use testes Unit para lógica isolada.
9. Execute os testes relevantes após mudanças. O projeto disponibiliza `composer test` e `php artisan test`; os testes usam SQLite em memória conforme `phpunit.xml`.
10. Não modifique arquivos em `vendor/`, arquivos `.env` locais ou artefatos gerados como parte de uma tarefa comum.

## Áreas funcionais

- **Autenticação:** cadastro, login, logout e verificação de e-mail.
- **Recuperação de senha:** solicitação e redefinição com código OTP.
- **Obras:** operações CRUD, busca e exportação individual ou coletiva em PDF.
- **Perfil:** consulta, atualização, alteração de senha e exclusão da conta.
- **Feedback:** envio autenticado de comentários.

Ao trabalhar em qualquer uma dessas áreas, confirme primeiro as rotas e middlewares aplicados e cubra os casos de sucesso, validação, não autenticado e não autorizado que forem relevantes.
