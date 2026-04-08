# Projeto Cinema

Portal de notícias em PHP com MVC simples, autenticação de usuários, CRUD de notícias e avaliações.

## Funcionalidades

- Cadastro, login e logout de usuários
- Edição e exclusão de conta
- Cadastro, edição, exclusão e listagem de notícias
- Página individual de notícia
- Avaliação com estrelas e média de avaliação
- Proteção de páginas privadas via middleware
- Interface responsiva com tema cinema/streaming

## Pastas

- `public/` — páginas públicas
- `private/` — páginas protegidas
- `user/` — edição e exclusão de usuário
- `config/` — conexão e funções auxiliares
- `middleware/` — verificação de login
- `models/` — acesso aos dados
- `controllers/` — regras de negócio
- `views/` — layout reutilizável
- `assets/` — CSS, JS e imagens
- `database/` — dump do banco de dados

## Instruções

1. Crie o banco de dados a partir de `database/dump.sql`.
2. Ajuste `config/conexao.php` se necessário.
3. Abra `http://localhost/ProjetoCinema/` no navegador.
4. Acesse `public/login.php` ou `public/cadastro.php` para começar.
