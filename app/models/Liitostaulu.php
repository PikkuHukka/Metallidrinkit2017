<?php

class Liitostaulu extends BaseModel {

    public $drinkki, $ainesosa;

    public static function haeDrinkit($haku) {
        $query = DB::connection()->prepare('SELECT * FROM Drinkki INNER JOIN Liitostaulu ON Drinkki.id = Liitostaulu.drinkki WHERE Liitostaulu.ainesosa = :ainesosa ');
        $query->execute(array('ainesosa' => $haku));

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

    public static function haeAinesosat($drinkki) {
        $query = DB::connection()->prepare('SELECT * FROM Ainesosa INNER JOIN Liitostaulu ON Ainesosa.id = Liitostaulu.ainesosa WHERE Liitostaulu.drinkki = :drinkki ');
        $query->execute(array('drinkki' => $drinkki));

        $rows = $query->fetchAll();

        $drinkit = array();

        foreach ($rows as $row) {
            $drinkit[] = new Drinkki(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
            ));
        }



        return $drinkit;
    }

    public function lisaa() {
        $query = DB::connection()->prepare('INSERT INTO Liitostaulu (drinkki, ainesosa) VALUES (:drinkki, :ainesosa)');
        $query->execute(array('drinkki' => $this->drinkki, 'ainesosa' => $this->ainesosa));
    }

    public static function poista($drinkki) {
        $query = DB::connection()->prepare('DELETE FROM Liitostaulu WHERE drinkki = :drinkki');
        $query->execute(array('drinkki' => $drinkki));
    }

}
