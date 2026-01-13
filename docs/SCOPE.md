# 📘 MotorMundo — Documento de Escopo do Projeto

## 1. Visão Geral

**MotorMundo** é um sistema de simulação e gerenciamento de mundos fictícios (RPG / worldbuilding), focado em:

- modelagem demográfica multi-espécie
- culturas e biologia diferenciadas
- linhas do tempo
- tradução (I18n)
- integração com ferramentas externas (ex: Azgaar)
- rastreabilidade completa de dados (logs e auditoria)

O sistema é **orientado a domínio**, não a CRUDs genéricos.

---

## 2. Princípios Arquiteturais

### 2.1 Fonte Única de Verdade
- O **schema do banco de dados** é a fonte definitiva
- **DTOs** refletem exatamente o schema
- **Views nunca inventam dados**
- Nenhuma regra de negócio fica em HTML ou SQL solto

---

### 2.2 Separação de Responsabilidades

| Camada | Responsabilidade |
|------|------------------|
| View | Renderização e interação |
| Repository | Persistência e leitura |
| DTO | Contrato de dados |
| Service (futuro) | Regras de negócio |
| Database | Infraestrutura |

---

## 3. Regras de Desenvolvimento (Obrigatórias)

### 3.1 Repositories

- Todo Repository **DEVE implementar uma Interface**
- Interfaces definem **métodos obrigatórios**
- Repositories **NUNCA** geram HTML
- Views **NUNCA** executam SQL
- Toda entidade persistente possui um Repository dedicado

Interfaces obrigatórias:
- `RepositoryInterface`
- `TranslatableRepositoryInterface` (quando aplicável)

---

### 3.2 DTOs

- DTOs são **contratos imutáveis**
- Se um campo não está no DTO, a View **não pode acessá-lo**
- Propriedades usam `camelCase`
- DTOs **não possuem lógica de negócio**
- DTOs representam uma linha de tabela ou uma projeção clara

---

### 3.3 Tradução (I18n)

- Todo texto humano **DEVE ser traduzível**
- Tabelas base **NÃO** possuem campos traduzíveis
- Traduções vivem em tabelas dedicadas (`*_translations`)
- Tradução é sempre:
  - `entity_id`
  - `language_code`
  - campos textuais
- Nunca misturar múltiplos idiomas na mesma tela

---

## 3.4 CRUD Web (Padrão Obrigatório)

Toda página CRUD **DEVE** seguir padrões obrigatórios de UX, segurança e rastreabilidade.

---

### 3.4.1 `list.php`

Toda página de listagem **DEVE** conter:

- `<h1>` com título traduzido
- seletor de idioma (quando a entidade for traduzível)
- botão **Novo / Criar**
- listagem dos registros
- ações por registro:
  - **Editar**
  - **Histórico** (quando houver auditoria)

> Nenhuma listagem pode depender exclusivamente de menus globais para criação de registros.

---

### 3.4.2 `create.php`

Toda página de criação **DEVE**:

- utilizar `layout.php`
- possuir verificação de ACL
- utilizar Repositories (nunca SQL direto)
- registrar ação no `ActionLogger`
- **possuir botão “Voltar”**
- **NÃO** possuir botão “Histórico” (registro ainda não existe)

---

### 3.4.3 `edit.php`

Toda página de edição **DEVE obrigatoriamente conter**:

- utilização de `layout.php`
- verificação de ACL
- carregamento do registro via Repository
- suporte a tradução (quando aplicável)
- registro de alterações no `ActionLogger`
- registro de mudanças no `EntityAuditLogger`
- **botão “Voltar”**
- **botão “Histórico” (OBRIGATÓRIO)**

#### Botão Histórico
- Deve apontar para a página de auditoria
- URL padrão: /logs.php?entity=<entity_name>&id=<record_id>
- Deve permitir visualizar:
- ações realizadas
- alterações antes/depois
- usuário responsável
- data/hora

> **Regra de Ouro:**  
> Se um registro pode ser editado, seu histórico **deve ser acessível na mesma tela**.

---

### 3.4.4 Proibições

É **expressamente proibido**:

- acessar o banco diretamente em `public/`
- ocultar ações obrigatórias por decisão de layout
- editar registros sem auditoria
- criar CRUD sem logs
- misturar idiomas em uma mesma interface

---

### 3.4.5 Checklist de Validação de CRUD

Antes de considerar um CRUD como “concluído”, verificar:

- [ ] list.php possui botão **Novo**
- [ ] edit.php possui botão **Voltar**
- [ ] edit.php possui botão **Histórico**
- [ ] create.php possui botão **Voltar**
- [ ] ACL aplicada
- [ ] ActionLogger registrado
- [ ] EntityAuditLogger registrado
- [ ] Traduções funcionando

CRUD que não cumprir este checklist **não deve avançar**.

---

## 4. Logs e Auditoria

### 4.1 ActionLogger
- Registra **ações do usuário**
- Ex: create, update, delete, view
- Sempre associado a:
- usuário
- ação
- entidade
- registro
- timestamp

---

### 4.2 EntityAuditLogger
- Registra **ciclo de vida das entidades**
- Armazena:
- estado anterior
- estado posterior
- Sempre associado a:
- entidade
- ID do registro
- usuário
- data/hora

---

## 5. Segurança e Acesso (ACL)

- Toda página pública passa por `Acl::check()`
- ACL é definida por:
- papel
- ação
- Falha de ACL **interrompe a execução**
- Não renderizar HTML em caso de falha

---

## 6. Fluxo de Desenvolvimento (Golden Standard)

Nenhuma entidade nova pode ser criada fora desta ordem:

1. Schema congelado
2. DTO definido
3. Interface do Repository definida
4. Repository implementado
5. Script de teste (CLI / PHP simples)
6. CRUD Web
7. Logs e auditoria
8. Revisão de UX

---

## 7. Integrações Externas (Azgaar)

- Azgaar **NÃO é fonte de verdade**
- Dados externos são importados/exportados
- Mapeamentos explícitos (`azgaar_mapping`)
- Nenhuma dependência direta de runtime

---

## 8. Evolução do Projeto

Este documento:

- é normativo
- deve evoluir com o projeto
- toda mudança arquitetural **DEVE** ser refletida aqui
- decisões importantes não vivem apenas no código

> **Código sem escopo documentado é dívida técnica.**

---

## 9. Status Atual

- I18n: ✅ estabilizado
- ACL: ✅ funcional
- Logs/Auditoria: 🔄 em consolidação
- biological_sexes: 🔄 refatoração
- magic_energies: 🔄 padronização
- species: 🟢 próxima entidade oficial

