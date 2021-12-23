# notes
Aplikacja z systemem rejestracji pozwalająca zalogowanym użytkownikom zarządzanie notatkami. 

Aby otworzyć trzeba skonfigurować hosta dla xampp, skonfigurować bazy danych - jak w config.php lub zmienić na swoje dane. 
Baza danych działa na tabelach notes3 i users które trzeba utworzyć:

CREATE TABLE notes3 (
id INT NOT NULL AUTO_INCREMENT,
user_id INTEGER NOT NULL,
title VARCHAR(50),
description TEXT NOT NULL,
created TIMESTAMP NOT NULL ,
FOREIGN KEY(user_id) REFERENCES users(id),
PRIMARY KEY (`id`));

CREATE TABLE users(
id INT NOT NULL AUTO_INCREMENT,
name VARCHAR(50),
password VARCHAR(150),
PRIMARY KEY (id)
);


