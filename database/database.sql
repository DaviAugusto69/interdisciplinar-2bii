CREATE DATABASE meu_mvc CHARACTER SET utf8mb4;
USE meu_mvc;

CREATE TABLE users (
  id    INT AUTO_INCREMENT PRIMARY KEY,
  name  VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS admins (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100) NOT NULL,
  email      VARCHAR(150) NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email) VALUES
  ('Ana',   'ana@exemplo.com'),
  ('Bruno', 'bruno@exemplo.com'),
  ('Carla', 'carla@exemplo.com');
