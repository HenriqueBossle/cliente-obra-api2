# Tarefas do ClienteObra API

Este arquivo registra o backlog do projeto e os achados da revisão de segurança. Os itens de segurança abaixo foram reportados pela revisão estática e ainda precisam de correção e validação.

## Achados de segurança

### Alta

- [x] **Impedir que segredos sejam copiados para imagens Docker**
  - **Correção:** `.dockerignore` agora exclui arquivos `.env*`, `auth.json` e metadados Git do contexto do build; também evita copiar `vendor` e `node_modules` locais.
  - **Atenção:** imagens construídas antes dessa proteção não são alteradas. Revise as imagens já publicadas e rotacione credenciais se alguma imagem tiver incluído segredos.

### Média

- [x] **Exigir nova verificação ao alterar o e-mail do perfil**
  - **Correção:** o endereço atual continua sendo usado até a confirmação do novo; o fluxo armazena o endereço pendente, envia um link assinado com expiração e só então atualiza o e-mail e o estado de verificação.
  - **Validação:** testes de API cobrem a alteração pendente, a confirmação assinada e a rejeição de um hash inválido.

## Backlog

### Prioridade alta

- [ ] **Cobrir o ciclo de vida de obras com testes de API**
  - Testar criação, listagem, consulta, atualização e exclusão autenticadas.
  - Confirmar que um usuário não consegue consultar ou alterar obras de outro usuário.
  - Verificar validação dos campos e o formato JSON devolvido pelos endpoints.

- [ ] **Documentar os contratos da API**
  - Registrar endpoints públicos e autenticados, métodos HTTP e parâmetros.
  - Documentar autenticação Sanctum, validações, códigos de resposta e exemplos de payloads.
  - Incluir cadastro/login, verificação de e-mail, recuperação de senha, perfil, obras e feedback.

### Prioridade média

- [ ] **Validar busca de obras nos bancos suportados**
  - Confirmar o comportamento da busca por texto no SQLite usado nos testes e no banco de produção.
  - Cobrir busca vazia, correspondência parcial e isolamento por usuário com testes.

- [ ] **Testar os fluxos de autenticação e e-mail**
  - Cobrir cadastro, login, verificação e reenvio de e-mail, além de recuperação por OTP.
  - Simular o serviço externo de e-mail nos testes e verificar respostas para falhas de integração.

- [ ] **Testar geração dos relatórios PDF**
  - Cobrir exportação individual e de todas as obras do usuário autenticado.
  - Verificar autorização, nome do arquivo e comportamento quando não há obras.

### Prioridade baixa

- [ ] **Documentar configuração e execução local**
  - Descrever requisitos, preparação do `.env`, migrações e comandos para desenvolvimento e testes.
  - Listar variáveis necessárias para e-mail sem incluir valores secretos.

## Critério de conclusão

Marque uma tarefa como concluída somente depois de implementar seu escopo, validar os critérios descritos e registrar qualquer decisão que altere o contrato público da API.
