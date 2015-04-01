create database tree;

CREATE TABLE categories (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `parent_id` INT NULL,
    `level` INT DEFAULT 0,
    `first_parent` INT NULL,
    `name` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE INDEX categories_tree ON categories (parent_id);

ALTER TABLE categories ADD CONSTRAINT categories_tree
    FOREIGN KEY (parent_id) REFERENCES categories (id) ON UPDATE CASCADE ON DELETE CASCADE;


CREATE TABLE positions (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `type_id` INT NOT NULL,
    `category_id` INT NOT NULL,
    `name` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE INDEX positions_type ON positions (type_id);

CREATE TABLE type (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

//заполняем таблички данными
//категории
INSERT INTO categories (name) VALUES ('Категория 1');
INSERT INTO categories (name) VALUES ('Категория 2');
INSERT INTO categories (name) VALUES ('Категория 3');
// корневые категории

select * from categories;
+----+-----------+----------------------+
| id | parent_id | name                 |
+----+-----------+----------------------+
|  1 |      NULL | Категория 1          |
|  2 |      NULL | Категория 2          |
|  3 |      NULL | Категория 3          |
+----+-----------+----------------------+
//дочерние категории
INSERT INTO categories VALUES
    (4, 1, 'Категория 1-1'),
    (5, 1, 'Категория 1-2'),
    (6, 4, 'Категория 1-1-1'),
    (7, 6, 'Категория 1-1-1-1'),
    (8, 2, 'Категория 2-1'),
    (9, 3, 'Категория 3-1'),
    (10, 3, 'Категория 3-2'),
    (11, 2, 'Категория 2-2');

//несколько типов

INSERT INTO type (name) VALUES ('Тип 1');
INSERT INTO type (name) VALUES ('Тип 2');
INSERT INTO type (name) VALUES ('Тип 3');

//позиции по крайним категориям (По ТЗ нет листьев на средних ветках)
//крайние ветки (5,7,8,9,10,11)
INSERT INTO positions (type_id, category_id, name) VALUES (1,5,'Позиция 1');
INSERT INTO positions (type_id, category_id, name) VALUES (2,5,'Позиция 2');
INSERT INTO positions (type_id, category_id, name) VALUES (3,7,'Позиция 3');
INSERT INTO positions (type_id, category_id, name) VALUES (1,7,'Позиция 1');
INSERT INTO positions (type_id, category_id, name) VALUES (2,7,'Позиция 2');
INSERT INTO positions (type_id, category_id, name) VALUES (3,8,'Позиция 3');
INSERT INTO positions (type_id, category_id, name) VALUES (1,8,'Позиция 4');
INSERT INTO positions (type_id, category_id, name) VALUES (2,9,'Позиция 5');
INSERT INTO positions (type_id, category_id, name) VALUES (3,9,'Позиция 6');
INSERT INTO positions (type_id, category_id, name) VALUES (1,10,'Позиция 7');
INSERT INTO positions (type_id, category_id, name) VALUES (2,11,'Позиция 8');
INSERT INTO positions (type_id, category_id, name) VALUES (3,11,'Позиция 9');

