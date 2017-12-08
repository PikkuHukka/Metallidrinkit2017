<?php

class Ainesosa extends BaseModel {

    public $id, $nimi;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Ainesosa');
        $query->execute();

        $rows = $query->fetchAll();

        $ainesosat = array();

        foreach ($rows as $row) {
            $ainesosat[] = new Kayttaja(array(
                'id' => $row['id'],
                'nimi' => $row['nimi']
            ));
        }

        return $ainesosat;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Ainesosa WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        if ($row) {
            $ainesosa = new Ainesosa(array(
                'id' => $row['id'],
                'nimi' => $row['nimi']
            ));
            return $ainesosa;
        }
        return null;
    }

}
