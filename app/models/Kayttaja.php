<?php

class Kayttaja extends BaseModel {

    public $id, $nimi, $salasana, $salasana2, $taso;

    public function __construct($attributes) {


        parent::__construct($attributes);
        $this->validators = array('validate_kayttaja');
    }

    public static function authenticate($name, $password) {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE nimi = :name AND salasana = :password LIMIT 1');
        $query->execute(array('name' => $name, 'password' => $password));
        $row = $query->fetch();
        if ($row) {

            $user = new Kayttaja(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'salasana' => $row['salasana']
            ));
            return $user;
            // Käyttäjä löytyi, palautetaan löytynyt käyttäjä oliona
        } else {
            return null;
        }
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE id = :id LIMIT 1');

        $query->execute(array('id' => $id));
        $row = $query->fetch();


        if ($row) {
            $user = new Kayttaja(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'salasana' => $row['salasana'],
                'taso' => $row['taso']
            ));
            return $user;
        } else {
            return null;
        }
    }

    public static function nimiKaytossa($nimi) {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE nimi = :nimi LIMIT 1 ');
        //Tätä versiota käytetään uuden käyttäjän luonnissa
        $query->execute(array('nimi' => $nimi));
        $row = $query->fetch();


        if ($row) {
            $user = new Kayttaja(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'salasana' => $row['salasana'],
                'taso' => $row['taso']
            ));
            return $user;
        } else {
            return null;
        }
    }

    public static function nimiKaytossaUpdate($nimi, $id) {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE nimi = :nimi AND id != :id LIMIT 1 ');
        //Tätä versiota käytetään käyttäjän updatessa
        //ID'tä tarvitaan, koska muuten kayttäjää ei voisi muokata vaihtamatta nimeään.
        //Nyt käyttäjän nimeä ei sekoiteta itseensä.
        $query->execute(array('nimi' => $nimi, 'id' => $id));
        $row = $query->fetch();


        if ($row) {
            $user = new Kayttaja(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'salasana' => $row['salasana'],
                'taso' => $row['taso']
            ));
            return $user;
        } else {
            return null;
        }
    }

    public static function returnName($id) {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE id = :id LIMIT 1');

        $query->execute(array('id' => $id));
        $row = $query->fetch();


        if ($row) {
            $user = $row['nimi'];

            return $user;
        } else {
            return null;
        }
    }

    public static function returnTaso($id) {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE id = :id LIMIT 1');

        $query->execute(array('id' => $id));
        $row = $query->fetch();


        if ($row) {
            $taso = $row['taso'];

            return $taso;
        } else {
            return null;
        }
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Kayttaja (nimi, salasana, salasana2, taso) VALUES (:nimi, :salasana, :salasana2, :taso)  RETURNING id');
        $query->execute(array('nimi' => $this->nimi, 'salasana' => $this->salasana, 'salasana2' => $this->salasana2, 'taso' => $this->taso));

        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function validate_kayttaja() {
        $errors = array();

        if ($this->id == null) {
            if (kayttaja::nimiKaytossa($this->nimi)) {
                $errors[] = 'Tunnus on jo olemassa.';
            }
        } else {
            if (kayttaja::nimiKaytossaUpdate($this->nimi, $this->id)) {
                $errors[] = 'Tunnus on jo olemassa.';
            }
        }
        if (strlen($this->nimi) < 4) {
            $errors[] = 'Tunnuksen tulee olla vähintään kolme merkkiä!';
        }
        if ($this->salasana != $this->salasana2) {
            $errors[] = 'Salasanat eivät täsmää!';
        }

        if (strlen($this->salasana) < 5) {
            $errors[] = 'Salasanan pituus pitää olla vähintään 5!';
        }
        return $errors;
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja');
        $query->execute();

        $rows = $query->fetchAll();

        $kayttajat = array();

        foreach ($rows as $row) {
            $kayttajat[] = new Kayttaja(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'taso' => $row['taso']
            ));
        }

        return $kayttajat;
    }

    public static function destroy($id) {
        $query = DB::connection()->prepare('DELETE FROM Kayttaja WHERE id = :id');

        $query->execute(array('id' => $id));
    }

    public static function search($hakusanat) {

        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE LOWER(nimi) LIKE LOWER(:hakusanat)');

        $query->execute(array('hakusanat' => '%' . $hakusanat . '%'));


        $rows = $query->fetchAll();

        $drinkit = array();

        foreach ($rows as $row) {
            $drinkit[] = new Kayttaja(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'taso' => $row['taso']
            ));
        }

        return $drinkit;
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Kayttaja SET nimi = :nimi, taso = :taso WHERE id = :id');
        $query->execute(array('id' => $this->id, 'nimi' => $this->nimi, 'taso' => $this->taso));
    }

    public function password() {
        $query = DB::connection()->prepare('UPDATE Kayttaja SET salasana = :salasana, salasana2 = :salasana2 WHERE id = :id');
        $query->execute(array('id' => $this->id, 'salasana' => $this->salasana, 'salasana2' => $this->salasana2));
    }

    public function getPassword($id) {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE id = :id LIMIT 1');

        $query->execute(array('id' => $id));
        $row = $query->fetch();


        if ($row) {
            $salasana = $row['salasana'];

            return $salasana;
        } else {
            return null;
        }
    }

}
