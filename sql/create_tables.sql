-- Lisää CREATE TABLE lauseet tähän tiedostoon

CREATE TABLE Drinkki(
  id SERIAL PRIMARY KEY,
  nimi varchar(100) NOT NULL, 
  lampo varchar(100),
  kuvaus varchar(200),
  vahvuus int NOT NULL,
  lisaaja varchar(100)
);

CREATE TABLE Kayttaja(
  id SERIAL PRIMARY KEY,
  nimi varchar(50)NOT NULL,
  salasana varchar(50) NOT NULL,
  salasana2 varchar(50) NOT NULL,
  taso varchar(10) NOT NULL

);

CREATE TABLE Ainesosa(
  id SERIAL PRIMARY KEY,
  nimi varchar(50) NOT NULL

);
    
CREATE TABLE Liitostaulu(
  drinkki integer REFERENCES Drinkki(id),
  ainesosa integer REFERENCES Ainesosa(id)

);
