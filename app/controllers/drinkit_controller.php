<?php

class DrinkkiController extends BaseController {

    public static function index() {
        View::make('home.html');
    }

    public static function login() {
        View::make('login.html');
    }

    public static function lisays() {

        $ainesosat = Ainesosa::all();
        View::make('lisays.html', array('ainesosat' => $ainesosat));
    }

    public static function drinkit() {
        $drinkit = Drinkki::all();
        View::make('drinkit.html', array('drinkit' => $drinkit));
    }

    public static function drinkitHaku() {


        $params = $_POST;

        $attributes = $params['hakusanat'];
        $drinkit = Drinkki::search($attributes);
        View::make('drinkit.html', array('drinkit' => $drinkit));
    }

    public static function show($id) {
        $ainesosat = Liitostaulu::haeAinesosat($id);
        $drinkit = Drinkki::find($id);
        View::make('esittely.html', array('drinkit' => $drinkit, 'ainesosat' => $ainesosat));
    }

    public static function edit($id) {
        $ainesosat = Ainesosa::all();
        $drinkit = Drinkki::find($id);

        View::make('edit.html', array('drinkit' => $drinkit, 'ainesosat' => $ainesosat));
    }

    public static function store() {
        $params = $_POST;
        $lisaaja = self::get_user_logged_in_name();
        $attributes = array(
            'nimi' => $params['nimi'],
            'kuvaus' => $params['kuvaus'],
            'vahvuus' => $params['vahvuus'],
            'lampo' => $params['lampo'],
            'lisaaja' => $lisaaja
        );

        $drinkki = new Drinkki($attributes);
        $errors = $drinkki->errors();

        if (count($errors) == 0) {

            $drinkki->save();
            if (array_key_exists('ainesosat', $params)) {
                $ainesosat = $params['ainesosat'];
                foreach ($ainesosat as $ainesosa) {
                    $liitos = new Liitostaulu(array(
                        'drinkki' => $drinkki->id,
                        'ainesosa' => $ainesosa
                    ));
//liitoksen tallentaminen
                    $liitos->lisaa();
                }
                Redirect::to('/drinkit/' . $drinkki->id, array('message' => 'Drinkki on lisätty sivulle!'));
            } else {
                $errors[] = 'Ainesosia pitää olla ainakin 1';

                $ainekset = Ainesosa::all();
                View::make('lisays.html', array('errors' => $errors, 'attributes' => $attributes, 'ainesosat' => $ainekset));
            }
        }
        $ainekset = Ainesosa::all();
        View::make('lisays.html', array('errors' => $errors, 'attributes' => $attributes, 'ainesosat' => $ainekset));
    }

    public static function update($id) {
        $params = $_POST;

        $attributes = array(
            'id' => $id,
            'nimi' => $params['nimi'],
            'kuvaus' => $params['kuvaus'],
            'vahvuus' => $params['vahvuus'],
            'lampo' => $params['lampo'],
        );

        $drinkki = new Drinkki($attributes);

        $errors = $drinkki->errors();

        if (count($errors) == 0) {

            $drinkki->update();


            if (array_key_exists('ainesosat', $params)) {

                $ainesosat = $params['ainesosat'];

                Liitostaulu::poista($id);

                foreach ($ainesosat as $ainesosa) {
                    $liitos = new Liitostaulu(array(
                        'drinkki' => $drinkki->id,
                        'ainesosa' => $ainesosa
                    ));

                    $liitos->lisaa();
                }


                Redirect::to('/drinkit/' . $drinkki->id, array('message' => 'Drinkkiä on muokattu onnistuneesti!'));
            } else {
                $errors[] = 'Ainesosia pitää olla ainakin 1';
                $ainekset = Ainesosa::all();
                Redirect::to('/drinkit/' . $drinkki->id . '/edit', array('errors' => $errors, 'attributes' => $attributes, 'ainesosat' => $ainekset));
            }
        }

        $ainekset = Ainesosa::all();
        Redirect::to('/drinkit/' . $drinkki->id . '/edit', array('errors' => $errors, 'attributes' => $attributes, 'ainesosat' => $ainekset));
    }

    public static function destroy($id) {
        $drinkit = new Drinkki(array('id' => $id));

        Liitostaulu::poista($id);

        $drinkit->destroy($id);

// Ohjataan käyttäjä pelien listaussivulle ilmoituksen kera
        Redirect::to('/drinkit', array('message' => 'Drinkki on poistettu!'));
    }

}
