# Padrões de Projeto - BiblioTech (Style Guide Técnico)

Este documento define as regras e convenções que toda a equipe deve seguir durante o desenvolvimento do sistema BiblioTech. O objetivo é garantir que o código seja limpo, padronizado e fácil de dar manutenção.

## 1. Regra de Ouro: Idioma
**Todo o código fonte será escrito em Português.** Isso inclui nomes de variáveis, funções, classes, tabelas de banco de dados e comentários. O objetivo é manter a consistência e evitar o uso de "portinglês".

---

## 2. Padrões de Arquitetura (MVC)
O projeto utilizará a arquitetura **MVC (Model-View-Controller)** para separar as responsabilidades do sistema:

* **Model (Modelo):** É onde o dado "mora". Responsável pela comunicação direta com o banco de dados e as regras de negócio. (Ex: Salvar um novo livro).
* **View (Visão):** É o que o usuário vê. São as nossas telas, botões e formulários feitos em HTML/CSS.
* **Controller (Controlador):** É o "cérebro" que liga a View ao Model. Ele recebe o clique do usuário na tela, pede a informação para o banco de dados e devolve o resultado para a tela.

---

## 3. Convenções de Nomenclatura (Naming Conventions)
Para evitar que cada programador escreva de um jeito diferente, usaremos os seguintes padrões:

* **PascalCase** -> Usado para **Classes** e **Models**.
  * *Exemplo:* `class Usuario`, `class CadastroLivro`
* **camelCase** -> Usado para **Variáveis** e **Funções**.
  * *Exemplo:* `const valorMulta`, `function calcularAtraso()`
* **snake_case** -> Usado exclusivamente para o **Banco de Dados** (tabelas e colunas).
  * *Exemplo:* `id_usuario`, `data_devolucao`, `tabela_emprestimos`
* **kebab-case** -> Usado para nomes de **Arquivos** e **Pastas**.
  * *Exemplo:* `cadastro-membros.html`, `style-dashboard.css`

---

## 4. Estrutura de Pastas
A organização do nosso repositório seguirá a seguinte estrutura base:

* `/docs` -> Toda a documentação do projeto (DER, Mapas, Guias).
* `/app` -> Código-fonte principal do sistema (Back-end e Controllers).
* `/public` -> Arquivos públicos (CSS, Imagens, JS).
* `/resources/views` -> Telas do sistema (Front-end).
* `README.md` -> Descrição geral e instruções de como rodar o projeto.

---

## 5. Comentários e Documentação de Código
Todo código complexo deve ser documentado para facilitar o entendimento da equipe:

* **Cabeçalho de Arquivo:** Arquivos principais devem ter o nome do autor e a data de criação no topo.
* **Comentários de Função:** Antes de uma função, explicar brevemente o que ela recebe e o que ela devolve.
  * *Exemplo:* `// Recebe o ID do livro e devolve o status de disponibilidade.`

---

## 6. Padrão de Commits (Git)
Para manter o histórico do GitHub organizado, todas as mensagens de commit devem usar as seguintes tags (em maiúsculo e entre colchetes) antes da explicação:

* `[FEAT]` -> Quando adicionar uma funcionalidade nova. (Ex: *[FEAT] Adicionando tela de login*)
* `[FIX]` -> Quando corrigir um erro ou bug. (Ex: *[FIX] Corrigindo cálculo da multa*)
* `[DOCS]` -> Quando alterar apenas arquivos de documentação. (Ex: *[DOCS] Atualizando o README*)
* `[UI]` -> Quando alterar apenas o visual/layout. (Ex: *[UI] Mudando cor do botão de salvar*)