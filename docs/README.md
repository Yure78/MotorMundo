# 🌍 MotorMundo

**MotorMundo** é um motor de simulação e gerenciamento de mundos fictícios, voltado para **RPG, worldbuilding e sistemas narrativos complexos**.

O projeto permite modelar, de forma estruturada e auditável:

- espécies com biologia distinta
- culturas e sociedades não humanas
- demografia avançada
- linhas do tempo alternativas
- magia, energia e recursos
- diplomacia, zonas e biomas
- integração com mapas externos (ex: Azgaar)

> MotorMundo não é um gerador aleatório.  
> É um **sistema de coerência interna** para mundos vivos.

---

## ✨ Principais Características

- 🧬 **Modelagem multi-espécie**
- 🕰️ **Linha do tempo versionada**
- 🌐 **Internacionalização (I18n) completa**
- 🧾 **Logs de ação e auditoria de entidades**
- 🔐 **Controle de acesso (ACL)**
- 🗺️ **Integração com Azgaar Fantasy Map Generator**
- 🧠 **Arquitetura orientada a domínio**
- 🧩 **Extensível para múltiplas linguagens no futuro**

---

## 🏗️ Arquitetura

O projeto segue uma arquitetura em camadas, com responsabilidades bem definidas:

/public → Interfaces web (entrypoints)
/src
├── DTO → Contratos de dados
├── Repository→ Acesso ao banco
├── Helpers → I18n, Logger, ACL
├── View → Layouts e partials
/docs
├── SCOPE.md → Documento normativo do projeto


### Princípios-chave
- **Fonte única de verdade**: banco de dados
- **DTOs como contratos**
- **Repositories com interfaces obrigatórias**
- **Views sem SQL**
- **Auditoria como regra, não exceção**

---

## 🌍 Internacionalização (I18n)

- Todo texto exibido ao usuário é traduzível
- Traduções são armazenadas em tabelas dedicadas
- Suporte a múltiplos idiomas simultaneamente
- Interface nunca mistura idiomas

---

## 🧾 Logs e Auditoria

O sistema registra:

### ActionLogger
- ações do usuário (create, update, delete, view)

### EntityAuditLogger
- ciclo de vida completo de registros
- antes/depois de alterações
- rastreabilidade por entidade

Cada registro editável **possui histórico obrigatório**.

---

## 🔐 Segurança (ACL)

- Todas as páginas passam por verificação de ACL
- Permissões são baseadas em papéis
- Acesso negado interrompe a execução
- Nenhuma interface é renderizada sem autorização

---

## 🧪 Fluxo de Desenvolvimento (Obrigatório)

Para criar uma nova entidade no MotorMundo:

1. Definir schema do banco
2. Criar DTO
3. Definir interface do Repository
4. Implementar Repository
5. Criar script de teste (CLI)
6. Criar CRUD web
7. Integrar logs e auditoria
8. Revisar UX

> CRUD criado fora dessa ordem **não é aceito**.

---

## 📘 Documentação

- 📄 **SCOPE.md** → documento normativo do projeto  
- 📄 **README.md** → visão geral e onboarding  

Toda decisão arquitetural relevante **deve ser refletida na documentação**.

---

## 🚧 Status do Projeto

- I18n: ✅ estabilizado
- ACL: ✅ funcional
- Logs/Auditoria: 🔄 em consolidação
- biological_sexes: 🔄 refatoração
- magic_energies: 🔄 padronização
- species: 🟢 próxima entidade

---

## 🛠️ Tecnologias

- PHP 7.4+ (compatível com 8.x)
- MariaDB / MySQL
- mysqli
- HTML/CSS (sem framework obrigatório)
- Markdown para documentação

---

## 📌 Observação Final

MotorMundo é um projeto **de longo prazo**, pensado para crescer em complexidade sem perder coerência.

> Se parece complexo, é porque o mundo é complexo.

---

## 📜 Licença

Copyleft (divirta-se e se puder mencionar minha autoria eu agradeça, valeu!)

