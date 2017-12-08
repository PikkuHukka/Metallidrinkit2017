<?php

class Drinkki extends BaseModel {

    public $id, $nimi, $lampo, $kuvaus, $vahvuus, $lisaaja, $ainesosat;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_kuvaus', 'validate_vahvuus', 'validate_nimi');
    }

    //Tarkistaa ettei kuvaus ole liian pitkä.
    public function validate_kuvaus() {
        $errors = array();

        if (strlen($this->kuvaus) > 500) {
            $errors[] = 'Kuvaus ei voi olla enintään 500 merkkiä!';
        }

        return $errors;
    }

    //Tarkistaa, että vahvuus on Jotain 100 ja 0 välissä.
    public function validate_vahvuus() {
        $errors = array();
        if ($this->vahvuus == null) {
            $errors[] = 'Vahvuus pitää olla 0-100 välissä!';
        }
        if ($this->vahvuus > 100 || $this->vahvuus < 0) {
            $errors[] = 'Vahvuus pitää olla 0-100 välissä!';
        }

        return $errors;
    }

    public function validate_kayttaja($muokkaaja) {
        $errors = array();

        if ($this->lisaaja != $muokkaaja) {
            $errors[] = 'Vain drinkin lisääjä voi muokkaa drinkkiä tai poistaa drinkin!';
        }

        return $errors;
    }

    //Hakee kaikki drinkit
    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Drinkki');
        $query->execute();

        $rows = $query->fetchAll();

        $drinkit = array();

        foreach ($rows as $row) {
            $drinkki = new Drinkki(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'lampo' => $row['lampo'],
                'kuvaus' => $row['kuvaus'],
                'vahvuus' => $row['vahvuus'],
                'lisaaja' => $row['lisaaja']
            ));

            $ainesosat = array();


            $liitokset = Liitostaulu::haeAinesosat($drinkki->id);
            foreach ($liitokset as $liitos) {
                $ainesosa = Ainesosa::find($liitos->id);
                $ainesosat[] = $ainesosa;
            }
            $drinkki->ainesosat = $ainesosat;
            $drinkit[] = $drinkki;
        }
        return $drinkit;
    }

//Hakee tietyillä sanoilla drinkkejä
    public static function search($hakusanat) {

        $query = DB::connection()->prepare('SELECT * FROM Drinkki WHERE LOWER(nimi) LIKE LOWER(:hakusanat)');

        $query->execute(array('hakusanat' => '%' . $hakusanat . '%'));


        $rows = $query->fetchAll();

        $drinkit = array();
        foreach ($rows as $row) {
            $drinkki = new Drinkki(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'lampo' => $row['lampo'],
                'kuvaus' => $row['kuvaus'],
                'vahvuus' => $row['vahvuus'],
                'lisaaja' => $row['lisaaja']
            ));

            $ainesosat = array();


            $liitokset = Liitostaulu::haeAinesosat($drinkki->id);
            foreach ($liitokset as $liitos) {
                $ainesosa = Ainesosa::find($liitos->id);
                $ainesosat[] = $ainesosa;
            }
            $drinkki->ainesosat = $ainesosat;
            $drinkit[] = $drinkki;
        }
        return $drinkit;
    }

//Etsii yhden 
    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Drinkki WHERE id = :id LIMIT 1');

        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $drinkit[] = new Drinkki(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'lampo' => $row['lampo'],
                'kuvaus' => $row['kuvaus'],
                'vahvuus' => $row['vahvuus'],
                'lisaaja' => $row['lisaaja']
            ));

            return $drinkit;
        }

        return null;
    }

//Tallentaa tietokantaan drinkin
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Drinkki (nimi, kuvaus, vahvuus, lampo, lisaaja) VALUES (:nimi, :kuvaus, :vahvuus, :lampo, :lisaaja) RETURNING id');
        $query->execute(array('nimi' => $this->nimi, 'kuvaus' => $this->kuvaus, 'vahvuus' => $this->vahvuus, 'lampo' => $this->lampo, 'lisaaja' => $this->lisaaja));

        $row = $query->fetch();
        $this->id = $row['id'];
    }

//Muokkaa drinkkiä
    public function update() {
        $query = DB::connection()->prepare('UPDATE Drinkki SET nimi = :nimi, kuvaus = :kuvaus, vahvuus = :vahvuus, lampo = :lampo WHERE id = :id');
        $query->execute(array('id' => $this->id, 'nimi' => $this->nimi, 'kuvaus' => $this->kuvaus, 'vahvuus' => $this->vahvuus, 'lampo' => $this->lampo));
    }

//Poistaa drinkin
    public static function destroy($id) {
        $query = DB::connection()->prepare('DELETE FROM Drinkki WHERE id = :id');

        $query->execute(array('id' => $id));
    }

}
