# 🤝 Contribuindo com o MotorMundo

Obrigado por seu interesse em contribuir com o **MotorMundo**.

Este documento define **regras obrigatórias** para contribuições, com foco em:
- consistência arquitetural
- qualidade do código
- redução de retrabalho
- rastreabilidade

> Contribuições que não seguirem estas regras **não serão aceitas**.

---

## 🧠 Princípio Fundamental

MotorMundo é um projeto **orientado a domínio**, não um CRUD genérico.

Toda contribuição deve:
- respeitar o **SCOPE.md**
- preservar coerência do sistema
- priorizar clareza sobre “atalhos”

---

## 🏗️ Estrutura do Projeto

Antes de contribuir, familiarize-se com:

- `README.md` → visão geral
- `SCOPE.md` → documento normativo (OBRIGATÓRIO)
- `PROGRESS.md` → status atual do projeto

---

## 📐 Regras Arquiteturais (Obrigatórias)

### 1. Novas Entidades

Nenhuma entidade nova pode ser criada fora da seguinte ordem:

1. Definição do schema do banco
2. Criação do DTO
3. Definição da interface do Repository
4. Implementação do Repository
5. Script de teste (CLI / PHP simples)
6. CRUD Web
7. Logs e auditoria
8. Revisão de UX

> Pular etapas **não é permitido**.

---

### 2. Repositories

- DEVEM implementar uma interface
- NÃO podem gerar HTML
- NÃO podem acessar `$_GET`, `$_POST`, `$_SESSION`
- NÃO podem conter lógica de negócio complexa

---

### 3. DTOs

- DTOs são contratos
- Não devem conter lógica
- Propriedades em `camelCase`
- Refletem fielmente o schema

---

### 4. CRUD Web

Todas as páginas CRUD devem seguir o padrão definido em `SCOPE.md`, incluindo:

#### `list.php`
- botão **Novo**
- botão **Histórico** (quando aplicável)

#### `create.php`
- botão **Voltar**
- sem botão Histórico

#### `edit.php`
- botão **Voltar**
- botão **Histórico** (obrigatório)

---

### 5. Tradução (I18n)

- Todo texto exibido ao usuário deve usar `I18n::t()`
- Nunca inserir texto fixo em HTML
- Traduções vivem em tabelas dedicadas

---

### 6. Logs e Auditoria

Toda ação relevante deve:
- registrar ação no `ActionLogger`
- registrar alterações no `EntityAuditLogger`

CRUD sem log **não é aceito**.

---

## 🧪 Testes

- Toda entidade nova deve ter ao menos um script de teste simples
- Scripts de teste **não** ficam em `public/`
- Testes servem para validar Repository e DTO, não UI

---

## 🔐 Segurança

- Toda página pública deve chamar `Acl::check()`
- Falha de ACL deve interromper a execução

---

## 📦 Commits

Recomendações de commit:

- mensagens claras e objetivas
- evitar commits gigantes
- separar refatorações de novas funcionalidades

Exemplo:
eat(species): add repository and translation support
fix(i18n): correct translation loading for edit pages


---

## 🧾 Documentação

Toda mudança arquitetural relevante **DEVE** ser refletida em:

- `SCOPE.md`
- ou documentação complementar

Código sem documentação gera dívida técnica.

---

## ❗ Regra Final

> Se você precisa perguntar  
> “posso fazer diferente do padrão?”  
> a resposta provavelmente é **não**.

Obrigado por contribuir para um projeto consistente e de longo prazo.

