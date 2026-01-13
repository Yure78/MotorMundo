# ✅ MotorMundo — Checklist de Normalização do Projeto

> Objetivo:
> **Sair do estado experimental para um estado estável, previsível e evolutivo**,
> sem sacrificar a ousadia do modelo.

---

## 🔴 PRIORIDADE 0 — CONGELAMENTO CONTROLADO (IMEDIATO)

> **Antes de qualquer refatoração**

* [ ] ❌ **Congelar criação de novos CRUDs**
* [ ] ❌ **Congelar novas entidades**
* [ ] ❌ **Congelar novas regras de negócio**
* [ ] ✅ Permitir apenas:

  * correções estruturais
  * criação de contratos
  * documentação

📌 *Motivo:* evitar multiplicar inconsistências.

---

## 🔴 PRIORIDADE 1 — CONTRATOS ESTRUTURAIS (OBRIGATÓRIO)

### 1.1 Repositories (Base do Problema Atual)

* [ ] Criar **interfaces de Repository** (`*RepositoryInterface`)
* [ ] Definir métodos mínimos obrigatórios:

  * `find(int $id)`
  * `findAll()`
  * `create(DTO $dto)`
  * `update(DTO $dto)`
  * `delete(int $id)`
* [ ] Fazer todos os Repositories **implementarem interfaces**
* [ ] Remover qualquer método “solto” fora do contrato

📌 *Resultado esperado:*

> Erros aparecem em tempo de desenvolvimento, não em runtime.

---

### 1.2 DTOs (Entidades Puras)

* [ ] Garantir que **DTOs não contenham**:

  * traduções
  * descrições de view
  * joins implícitos
* [ ] Revisar DTOs existentes:

  * `BiologicalSex`
  * `MagicEnergy`
  * `Species`
* [ ] Eliminar acessos como:

  ```php
  $item->translation
  $item->description
  ```

📌 *Regra de ouro:*

> Se a View precisa, a Repository deve fornecer explicitamente.

---

## 🔴 PRIORIDADE 2 — PIPELINE CORRETO (EVITA 80% DOS BUGS)

### 2.1 Script de Teste CLI (OBRIGATÓRIO)

Para **cada entidade**, antes do CRUD:

* [ ] Criar script CLI de teste (`/tests/manual/*.php`)
* [ ] Testar:

  * insert
  * update
  * delete
  * tradução
* [ ] Proibir CRUD sem teste prévio

📌 *Motivo:*
Você está depurando HTML quando deveria depurar SQL.

---

## 🟠 PRIORIDADE 3 — REFATORAÇÃO GUIADA (UMA ENTIDADE)

### 3.1 Entidade Piloto: `species`

* [ ] Refazer `species` **do zero**, seguindo a ordem:

  ```
  Schema → DTO → Repository → Teste → CRUD
  ```
* [ ] Implementar:

  * list.php
  * create.php
  * edit.php
* [ ] Verificar checklist CRUD (ver PRIORIDADE 6)

📌 *Objetivo:* criar **modelo exemplar** para todas as outras.

---

## 🟠 PRIORIDADE 4 — I18N (PADRONIZAÇÃO DEFINITIVA)

* [ ] Definir **contrato único** para Repositories de tradução
* [ ] Padronizar métodos:

  * `findByEntityAndLanguage()`
  * `upsert()`
* [ ] Definir fallback oficial:

  * idioma padrão
  * código da entidade
* [ ] Garantir que:

  * list.php **mostra tradução**
  * edit.php **edita tradução**
* [ ] Eliminar SQL direto em páginas `public/`

📌 *Resultado:*
Interfaces previsíveis em qualquer idioma.

---

## 🟠 PRIORIDADE 5 — LOGS E AUDITORIA (RIGOR TOTAL)

### 5.1 ActionLogger

* [ ] Garantir uso em:

  * create
  * update
  * delete
  * view (opcional)
* [ ] Registrar:

  * usuário
  * ação
  * entidade
  * ID

---

### 5.2 EntityAuditLogger

* [ ] Registrar **before / after**
* [ ] Associar:

  * entidade
  * ID
  * campo alterado
* [ ] Garantir acesso via:

  ```
  /logs.php?entity=X&id=Y
  ```

📌 *Regra:*

> Se pode editar, pode auditar.

---

## 🟡 PRIORIDADE 6 — PADRÃO DE CRUD (UX + GOVERNANÇA)

### Checklist obrigatório por CRUD

* [ ] list.php:

  * botão **Novo**
  * botão **Histórico** por registro
* [ ] create.php:

  * botão **Voltar**
* [ ] edit.php:

  * botão **Voltar**
  * botão **Histórico** (OBRIGATÓRIO)
* [ ] Todos:

  * ACL
  * layout.php
  * I18n

---

## 🟡 PRIORIDADE 7 — VIEWMODELS / PROJEÇÕES

* [ ] Definir padrão para:

  * listagens traduzidas
  * joins explícitos
* [ ] Nunca “injetar” dados extras no DTO base
* [ ] Separar:

  * domínio
  * apresentação

📌 *Evita erros como o do `magic_energies/list.php`.*

---

## 🟢 PRIORIDADE 8 — DOCUMENTAÇÃO (AGORA COM PESO REAL)

* [ ] Atualizar `README.md` (visão geral)
* [ ] Consolidar regras em `SCOPE.md`
* [ ] Criar / expandir:

  * `WORLD_MODEL.md` (riqueza do mundo)
  * `NORMALIZATION_CHECKLIST.md` (este documento)
* [ ] Garantir que documentação **reflete o código real**

---

## 🟢 PRIORIDADE 9 — EVOLUÇÃO SEGURA (FUTURO)

Somente após tudo acima:

* [ ] Criar camada `Service`
* [ ] Criar simulações de tempo
* [ ] Criar cálculos sociais e mágicos
* [ ] Expandir diplomacia e infraestrutura

---

# 🧠 REGRA FINAL (NÃO NEGOCIÁVEL)

> **Nenhuma nova entidade pode ser criada
> enquanto existir uma entidade quebrada.**

Esse checklist é o **freio e o acelerador** do MotorMundo.

Se quiser, próximo passo posso:

* transformar isso em **issues do Git**
* criar um **kanban lógico**
* ou começar **agora** pela refatoração exemplar de `species`
