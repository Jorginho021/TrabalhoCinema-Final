# 🎬 Projeto Cinema

Sistema completo de Portal de Notícias sobre filmes desenvolvido em PHP, utilizando arquitetura MVC simplificada.


## 📌 Sobre o Projeto

O Projeto Cinema é uma aplicação web que simula um portal de notícias de filmes, com funcionalidades como autenticação de usuários, CRUD completo e organização em camadas.

Ideal para aprendizado de:
- PHP
- MySQL
- Arquitetura MVC
- CRUD
- Autenticação

---

## 🚀 Funcionalidades

### 👤 Usuários
- Cadastro de usuário
- Login e logout
- Edição de conta
- Exclusão de conta

### 📰 Notícias
- Criar notícias
- Editar notícias
- Excluir notícias
- Listar notícias
- Visualizar notícia individual

### ⭐ Avaliações
- Avaliação com estrelas
- Média de avaliações

### 🔒 Segurança
- Proteção de páginas privadas (middleware)
- Controle de acesso por login

### 🎨 Interface
- Layout responsivo
- Tema inspirado em cinema/streaming

---

## 🗂️ Estrutura de Pastas

ProjetoCinema/

├── public/        # Páginas públicas  
├── private/       # Páginas protegidas  
├── user/          # Gerenciamento de usuário  
├── config/        # Configurações e conexão  
├── middleware/    # Verificação de login  
├── models/        # Acesso ao banco de dados  
├── controllers/   # Regras de negócio  
├── views/         # Layout e interface  
├── assets/        # CSS, JS e imagens  
└── database/      # Banco de dados  

---

## ⚙️ Como Executar
Acessando o Link: https://jorge021.infinityfree.me/public/index.php
---

## 🧠 Tecnologias Utilizadas

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript

---

## 📎 Observação

O arquivo index.php principal apenas redireciona para a pasta pública:

header('Location: public/index.php');

---

## 🎯 Objetivo

Projeto desenvolvido para fins acadêmicos com foco em:

- CRUD
- MVC
- Autenticação
- Integração com banco de dados
