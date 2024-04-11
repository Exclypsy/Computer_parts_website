CREATE DATABASE db_eshop
GO

USE db_eshop
GO

CREATE TABLE contact (
  id_contact INT NOT NULL PRIMARY KEY,
  cele_meno VARCHAR(100) NOT NULL,
  subjekt VARCHAR(255) NULL,
  mail VARCHAR(255) NOT NULL,
  tel_c VARCHAR(20) NOT NULL,
  obsah_spravy TEXT NULL
);
GO

INSERT INTO contact (id_contact, cele_meno, subjekt, mail, tel_c, obsah_spravy) VALUES
(5, 'Oliver', 'Procesor', 'gmjqziarmfoinbbzzw@cazlv.com', '948654789', 'Lorem Ipsum iii'),
(6, 'Martin', 'Graficka karta', 'martinbartko07@gmail.com', '948654789', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
GO

CREATE TABLE dorucenie (
  ID_dorucenie INT NOT NULL PRIMARY KEY,
  typ_dorucenia VARCHAR(255) NOT NULL
);
GO

INSERT INTO dorucenie (ID_dorucenie, typ_dorucenia) VALUES
(1, 'Slovenska Posta'),
(2, 'Kurier'),
(3, 'Baliko Box');
GO

CREATE TABLE kategorie (
  ID_kategoria INT NOT NULL PRIMARY KEY,
  nazov VARCHAR(255) NOT NULL
);
GO

INSERT INTO kategorie (ID_kategoria, nazov) VALUES
(1, 'Procesory'),
(2, 'Grafické karty'),
(3, 'RAM pamet'),
(4, 'Úložiská (HDD/SSD)'),
(5, 'Základné dosky'),
(6, 'Zdroje'),
(7, 'Chladenie'),
(8, 'PC skrine'),
(9, 'Optické mechaniky'),
(10, 'Klávesnice a myši'),
(11, 'Monitory'),
(12, 'Notebooky');
GO

CREATE TABLE kosik (
  ID_kosik INT NOT NULL PRIMARY KEY,
  ID_produk INT NULL,
  mnozstvo INT NULL,
  FOREIGN KEY (ID_produk) REFERENCES produkty(ID_produk)
);
GO

CREATE TABLE objednavka (
  ID_objednavka INT NOT NULL PRIMARY KEY,
  ID_platba INT NULL,
  ID_dorucenie INT NULL,
  meno VARCHAR(100) NOT NULL,
  priezvisko VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL,
  tel_c VARCHAR(20) NOT NULL,
  PSC VARCHAR(10) NOT NULL,
  Mesto VARCHAR(255) NOT NULL,
  Ulica VARCHAR(255) NOT NULL,
  Obsah_objednavky TEXT NOT NULL,
  cas_objednavky DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (ID_platba) REFERENCES platba(ID_platba),
  FOREIGN KEY (ID_dorucenie) REFERENCES dorucenie(ID_dorucenie)
);
GO

INSERT INTO objednavka (ID_objednavka, ID_platba, ID_dorucenie, meno, priezvisko, email, tel_c, PSC, Mesto, Ulica, Obsah_objednavky) VALUES
(1, NULL, NULL, 'Martin', 'Bartko', 'martinbartko07@gmail.com', '23145678', 'Presov', '08001', 'asffhbjf', 'Nvidia RTX 3070 Ti , Nvidia RTX 3060, ASUS ROG Strix GeForce RTX 3080');
GO

CREATE TABLE platba (
  ID_platba INT NOT NULL PRIMARY KEY,
  typ_platby VARCHAR(255) NOT NULL
);
GO

INSERT INTO platba (ID_platba, typ_platby) VALUES
(1, 'Dobierka'),
(2, 'Kartou Online'),
(3, 'Prevodom na ucet'),
(4, 'Darcekova poukazka');
GO

CREATE TABLE produkty (
  ID_produk INT NOT NULL PRIMARY KEY,
  nazov VARCHAR(255) NOT NULL,
  mnozstvo INT NOT NULL,
  cena DECIMAL(10,2) NOT NULL,
  popis TEXT NULL,
  ID_tax INT NULL,
  kod_produktu VARCHAR(50) NULL,
  ID_kategoria INT NULL,
  obrazok VARCHAR(255) NULL,
  FOREIGN KEY (ID_tax) REFERENCES tax(ID_tax),
  FOREIGN KEY (ID_kategoria) REFERENCES kategorie(ID_kategoria)
);
GO

INSERT INTO produkty (ID_produk, nazov, mnozstvo, cena, popis, ID_tax, kod_produktu, ID_kategoria, obrazok) VALUES
(1, 'Nvidia RTX 3070 Ti ', 30, 1240.99, 'Lorem ipsum', 2, 'F345678', 2, 'https://via.placeholder.com/550x750'),
(2, 'Nvidia RTX 3060', 23, 830.99, 'Lorem ipsum', 2, 'F468238', 2, 'https://via.placeholder.com/550x750'),
(3, 'AMD Ryzen 7 5800X', 15, 469.99, 'Lorem ipsum', 1, 'AMD5800X', 1, 'https://via.placeholder.com/550x750'),
(4, 'AMD Ryzen 7 7800X3D', 23, 390.99, 'Lorem Ipsum', 2, 'F323678', 1, 'https://via.placeholder.com/550x750'),
(5, 'ASUS ROG Strix GeForce RTX 3080', 20, 1799.99, 'Lorem ipsum', 2, 'ROG3080', 2, 'https://via.placeholder.com/550x750'),
(6, 'GIGABYTE Radeon RX 6900 XT', 25, 1499.99, 'Lorem ipsum', 2, 'RX6900XT', 2, 'https://via.placeholder.com/550x750'),
(7, 'Corsair Vengeance LPX 32GB (2x16GB) DDR4 3600MHz', 30, 169.99, 'Lorem ipsum', 3, 'Vengeance32GB', 3, 'https://via.placeholder.com/550x750'),
(8, 'Crucial P2 1TB NVMe SSD', 40, 99.99, 'Lorem ipsum', 3, 'P21TB', 4, 'https://via.placeholder.com/550x750'),
(9, 'ASUS ROG Strix B550-F Gaming', 15, 199.99, 'Lorem ipsum', 1, 'ROGB550F', 5, 'https://via.placeholder.com/550x750'),
(10, 'Seasonic Focus GX-850 850W 80+ Gold', 20, 129.99, 'Lorem ipsum', 2, 'FocusGX850', 6, 'https://via.placeholder.com/550x750'),
(11, 'Noctua NH-D15 chromax.black', 10, 99.99, 'Lorem ipsum', 1, 'NH-D15', 7, 'https://via.placeholder.com/550x750'),
(12, 'Fractal Design Meshify C', 18, 89.99, 'Lorem ipsum', 2, 'MeshifyC', 8, 'https://via.placeholder.com/550x750'),
(13, 'ASUS DRW-24B1ST DVD-RW', 25, 19.99, 'Lorem ipsum', 3, 'DRW24B1ST', 9, 'https://via.placeholder.com/550x750'),
(14, 'Logitech G Pro X Keyboard', 30, 129.99, 'Lorem ipsum', 2, 'GProXKB', 10, 'https://via.placeholder.com/550x750'),
(15, 'Razer DeathAdder V2 Gaming Mouse', 35, 69.99, 'Lorem ipsum', 3, 'DeathAdderV2', 10, 'https://via.placeholder.com/550x750'),
(16, 'ASUS TUF Gaming VG27AQ', 22, 399.99, 'Lorem ipsum', 2, 'TUFVG27AQ', 11, 'https://via.placeholder.com/550x750'),
(17, 'Acer Predator Helios 300 Gaming Laptop', 12, 1299.99, 'Lorem ipsum', 1, 'PredatorHelios300', 12, 'https://via.placeholder.com/550x750'),
(18, 'Intel Core i9-12900K', 10, 599.99, 'Lorem ipsum', 1, 'i912900K', 1, 'https://via.placeholder.com/550x750');
GO

CREATE TABLE tax (
  ID_tax INT NOT NULL PRIMARY KEY,
  dan DECIMAL(5,2) NOT NULL
);
GO

INSERT INTO tax (ID_tax, dan) VALUES
(1, 10.00),
(2, 20.00),
(3, 5.00);
GO

ALTER TABLE kosik ADD CONSTRAINT kosik_fk_produk FOREIGN KEY (ID_produk) REFERENCES produkty(ID_produk);
GO

ALTER TABLE objednavka ADD CONSTRAINT objednavka_fk_platba FOREIGN KEY (ID_platba) REFERENCES platba(ID_platba),
                       CONSTRAINT objednavka_fk_dorucenie FOREIGN KEY (ID_dorucenie) REFERENCES dorucenie(ID_dorucenie);
GO

ALTER TABLE produkty ADD CONSTRAINT produkty_fk_tax FOREIGN KEY (ID_tax) REFERENCES tax(ID_tax),
                        CONSTRAINT produkty_fk_kategoria FOREIGN KEY (ID_kategoria) REFERENCES kategorie(ID_kategoria);
GO

ALTER TABLE kosik 
ADD ID_objednavka INT NULL;
GO

ALTER TABLE kosik 
ADD CONSTRAINT kosik_fk_objednavka FOREIGN KEY (ID_objednavka) REFERENCES objednavka(ID_objednavka);
GO

-- Triggery
CREATE TRIGGER trg_zmaz_objednavku
ON objednavka
FOR DELETE
AS
BEGIN
    DELETE FROM kosik WHERE ID_kosik IN (SELECT ID_kosik FROM deleted);
END;
GO

CREATE TRIGGER trg_zmaz_otazku
ON kontakt
FOR DELETE
AS
BEGIN
    DELETE FROM kontakt
    WHERE id_contact IN (SELECT id_contact FROM deleted);
END;
GO


CREATE TRIGGER trg_zmaz_produkt
ON produkty
INSTEAD OF DELETE
AS
BEGIN
    DELETE FROM kosik WHERE ID_produk IN (SELECT ID_produk FROM deleted);
    DELETE FROM produkty WHERE ID_produk IN (SELECT ID_produk FROM deleted);
END;
GO



SELECT * FROM produkty WHERE ID_kategoria = 1; -- Procesory
GO
SELECT * FROM produkty WHERE ID_kategoria = 2; -- Grafické karty
GO
SELECT * FROM produkty WHERE ID_kategoria = 3; -- RAM pamäť
GO
SELECT * FROM produkty WHERE ID_kategoria = 4; -- Úložiská (HDD/SSD)
GO
SELECT * FROM produkty WHERE ID_kategoria = 5; -- Základné dosky
GO
SELECT * FROM produkty WHERE ID_kategoria = 6; -- Zdroje
GO
SELECT * FROM produkty WHERE ID_kategoria = 7; -- Chladenie
GO
SELECT * FROM produkty WHERE ID_kategoria = 8; -- PC skrine
GO
SELECT * FROM produkty WHERE ID_kategoria = 9; -- Optické mechaniky
GO
SELECT * FROM produkty WHERE ID_kategoria = 10; -- Klávesnice a myši
GO
SELECT * FROM produkty WHERE ID_kategoria = 11; -- Monitory
GO
SELECT * FROM produkty WHERE ID_kategoria = 12; -- Notebooky
GO

CREATE PROCEDURE ZvysMnozstvoVKosiku
    @ID_kosik INT,
    @PridaneMnozstvo INT
AS
BEGIN
    UPDATE kosik
    SET mnozstvo = mnozstvo + @PridaneMnozstvo
    WHERE ID_kosik = @ID_kosik;
END;
GO
