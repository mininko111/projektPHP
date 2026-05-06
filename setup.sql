-- ============================================================
--  BookShelf – SQL Setup Script
--  Spustite tento súbor raz pre vytvorenie databázy, tabuliek
--  a vloženie testovacích dát.
-- ============================================================

CREATE DATABASE IF NOT EXISTS bookshelf
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE bookshelf;

-- ------------------------------------------------------------
-- Tabuľka: authors (autori)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS authors (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(150) NOT NULL,
    born_year  SMALLINT     DEFAULT NULL,
    country    VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabuľka: books (knihy)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS books (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    author_id   INT          NOT NULL,
    genre       VARCHAR(80)  DEFAULT NULL,
    year        SMALLINT     DEFAULT NULL,
    rating      TINYINT      DEFAULT NULL CHECK (rating BETWEEN 1 AND 5),
    note        TEXT         DEFAULT NULL,
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_author FOREIGN KEY (author_id)
        REFERENCES authors(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Testovacie dáta – autori
-- ------------------------------------------------------------
INSERT INTO authors (name, born_year, country) VALUES
('J.R.R. Tolkien',   1892, 'Anglicko'),
('George Orwell',    1903, 'Anglicko'),
('Frank Herbert',    1920, 'USA'),
('Douglas Adams',    1952, 'Anglicko'),
('Umberto Eco',      1932, 'Taliansko'),
('Gabriel García Márquez', 1927, 'Kolumbia');

-- ------------------------------------------------------------
-- Testovacie dáta – knihy
-- ------------------------------------------------------------
INSERT INTO books (title, author_id, genre, year, rating, note) VALUES
('Pán prsteňov: Spoločenstvo prsteňa', 1, 'Fantasy',       1954, 5, 'Klasika žánru, povinná četba.'),
('Pán prsteňov: Dve veže',            1, 'Fantasy',       1954, 5, NULL),
('Pán prsteňov: Návrat kráľa',        1, 'Fantasy',       1955, 5, 'Neuveriteľné finále.'),
('1984',                              2, 'Dystopia',      1949, 5, 'Aktuálne aj dnes.'),
('Farma zvierat',                     2, 'Satira',        1945, 4, 'Krátke, no silné.'),
('Duna',                              3, 'Sci-Fi',        1965, 5, 'Najlepší sci-fi román všetkých čias.'),
('Stopár po Galaxii',                 4, 'Sci-Fi Humor',  1979, 5, '42.'),
('Meno ruže',                         5, 'Historický',    1980, 4, 'Detektívka v stredovekom kláštore.'),
('Sto rokov samoty',                  6, 'Magický realizmus', 1967, 5, 'Nóbel – absolútne majstrovstvo.');
