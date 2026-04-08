-- Dump inicial do banco de dados Projeto Cinema

CREATE DATABASE IF NOT EXISTS projeto_cinema;
USE projeto_cinema;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE noticias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255) NOT NULL,
  noticia TEXT NOT NULL,
  data DATETIME DEFAULT CURRENT_TIMESTAMP,
  autor INT NOT NULL,
  imagem VARCHAR(255) DEFAULT NULL,
  CONSTRAINT fk_noticias_autor FOREIGN KEY (autor) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE avaliacoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  noticia_id INT NOT NULL,
  usuario_id INT NOT NULL,
  estrelas TINYINT NOT NULL,
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_avaliacoes_noticia FOREIGN KEY (noticia_id) REFERENCES noticias(id) ON DELETE CASCADE,
  CONSTRAINT fk_avaliacoes_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
