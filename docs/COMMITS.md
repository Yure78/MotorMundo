# 📝 COMMITS — Padrão de Mensagens do MotorMundo

Este documento define o **padrão oficial de mensagens de commit** do projeto **MotorMundo**.

O objetivo é:
- manter histórico legível
- facilitar auditoria e refatorações
- permitir evolução sem perda de contexto
- preparar o projeto para automações futuras

> Commits são documentação viva.

---

## 📐 Estrutura Obrigatória

Toda mensagem de commit **DEVE** seguir o formato:
```text
<tipo>(<escopo>): <descrição curta>
```

Exemplo:
```
eat(species): add repository and translation support
```

---

## 🧩 Tipos de Commit (`<tipo>`)

| Tipo | Uso |
|----|----|
| `feat` | Nova funcionalidade |
| `fix` | Correção de bug |
| `refactor` | Refatoração sem mudança de comportamento |
| `docs` | Documentação |
| `chore` | Manutenção, config, limpeza |
| `test` | Testes |
| `style` | Formatação, CSS, ajustes visuais |
| `perf` | Melhoria de performance |
| `build` | Build, dependências, infra |

👉 **Escolha apenas um tipo por commit.**

---

## 🎯 Escopo (`<escopo>`)

O escopo indica **qual parte do sistema foi afetada**.

### Escopos comuns no MotorMundo

| Escopo | Significado |
|-----|-----------|
| `species` | Entidade species |
| `biological_sexes` | Entidade biological_sexes |
| `magic_energies` | Entidade magic_energies |
| `i18n` | Sistema de tradução |
| `acl` | Controle de acesso |
| `logs` | Logs e auditoria |
| `repository` | Repositories |
| `dto` | DTOs |
| `ui` | Interface |
| `layout` | Layouts e templates |
| `docs` | Documentação |
| `bootstrap` | Inicialização do sistema |

Escopos devem ser:
- minúsculos
- descritivos
- estáveis

---

## ✍️ Descrição (`<descrição curta>`)

A descrição deve ser:

- curta (ideal: até 72 caracteres)
- no **imperativo**
- clara
- sem pontuação final

### ✅ Exemplos corretos
```text
add repository and translation support
fix translation loading in edit pages
remove unused helper methods
update scope documentation
```

### ❌ Exemplos incorretos
```text
added new stuff
corrigi umas coisas
funciona agora
update
```

---

## 🧪 Exemplos Reais no Contexto do MotorMundo

```text
feat(species): add base entity and repositories
feat(i18n): add translation CRUD and cache builder
fix(i18n): correct language loading on edit pages
refactor(repository): enforce interface contracts
docs(scope): add mandatory history button to edit pages
chore(layout): clean up unused partials
```



