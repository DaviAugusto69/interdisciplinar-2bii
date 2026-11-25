-- Execute com uma conta administrativa, apenas na configuração inicial.
CREATE USER 'meu_mvc_app'@'localhost'
  IDENTIFIED BY 'substitua-por-uma-senha-local';
GRANT SELECT, INSERT, UPDATE, DELETE ON eda_database.*
  TO 'meu_mvc_app'@'localhost';
