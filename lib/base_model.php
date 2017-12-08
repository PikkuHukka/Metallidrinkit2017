<?php

class BaseModel {

// "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null) {
// Käydään assosiaatiolistan avaimet läpi
        foreach ($attributes as $attribute => $value) {
// Jos avaimen niminen attribuutti on olemassa...
            if (property_exists($this, $attribute)) {
// ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
                $this->{$attribute} = $value;
            }
        }
    }

    public function errors() {
// Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
        $errors = array();

        foreach ($this->validators as $validator) {
            $validator_errors = $this->{$validator}();
            $errors = array_merge($errors, $validator_errors);
        }


        return $errors;
    }

    public function validate_nimi() {
        $errors = array();
        if ($this->nimi == '' || $this->nimi == null || strlen($this->nimi) < 3) {

            $errors[] = 'Nimen pituuden tulee olla vähintään 3 merkkiä!';
        }
        if (strlen($this->nimi) > 50) {
            $errors[] = 'Nimen pituus ei saa olla yli 50 merkkiä!';
        }

        return $errors;
    }

}
