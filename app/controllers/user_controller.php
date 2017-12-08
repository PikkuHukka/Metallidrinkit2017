<?php

class UserController extends BaseController {

    public static function login() {
        View::make('login.html');
    }

    public static function handle_login() {
        $params = $_POST;

        $kayttaja = Kayttaja::authenticate($params['username'], $params['password']);

        if (!$kayttaja) {
            Redirect::to('/login', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'username' => $params['username']));
        } else {
            $id = $kayttaja->id;
            if (Kayttaja::returnTaso($id) == 'suljettu') {
                Redirect::to('/login', array('error' => 'Tämä käyttäjätili on suljettu!'));
            } else {

                $_SESSION['user'] = $kayttaja->id;
                Redirect::to('/', array('message' => 'Tervetuloa!'));
            }
        }
    }

    public static function logout() {
        $_SESSION['user'] = null;
        Redirect::to('/login', array('message' => 'Olet kirjautunut ulos!'));
    }

    public static function uusikayttaja() {
        View::make('uusikayttaja.html');
    }

    public static function luokayttaja() {
        $params = $_POST;
        $taso = 'perus';

        $attributes = array(
            'nimi' => $params['nimi'],
            'salasana' => $params['salasana'],
            'salasana2' => $params['salasana2'],
            'taso' => $taso
        );

        $kayttaja = new Kayttaja($attributes);
        $errors = $kayttaja->errors();

        if (count($errors) == 0) {

            $kayttaja->save();

            Redirect::to('/login', array('message' => 'Käyttäjä luotu. Voit nyt kirjautua sisälle.!'));
        } else {
            View::make('uusikayttaja.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function kayttajat() {
        $kayttajat = Kayttaja::all();
        View::make('kayttajahallinta.html', array('kayttajat' => $kayttajat));
    }

    public static function kayttajaHaku() {


        $params = $_POST;

        $attributes = $params['hakusanat'];
        $kayttajat = Kayttaja::search($attributes);
        View::make('kayttajahallinta.html', array('kayttajat' => $kayttajat));
    }

    public static function edit($id) {
        $kayttaja = Kayttaja::find($id);
        View::make('kayttajaneditointi.html', array('kayttaja' => $kayttaja));
    }

    public static function destroy($id) {
        $drinkit = new Kayttaja(array('id' => $id));
        $drinkit->destroy($id);

// Ohjataan käyttäjä pelien listaussivulle ilmoituksen kera
        Redirect::to('/kayttajahallinta', array('error' => 'Kayttäjätili on poistettu.'));
    }

    public static function update($id) {
        $params = $_POST;

        $salasana = Kayttaja::getPassword($id);
        $attributes = array(
            'id' => $id,
            'nimi' => $params['nimi'],
            'taso' => $params['taso'],
            'salasana' => $salasana,
            'salasana2' => $salasana
        );

        $user = new Kayttaja($attributes);

        $errors = $user->errors();

        if (count($errors) == 0) {
            $user->update();
            Redirect::to('/kayttajahallinta/' . $user->id . '/edit', array('message' => 'Käyttäjätiliä on muokattu!'));
        } else {
            Redirect::to('/kayttajahallinta/' . $user->id . '/edit', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function salasananVaihto($id) {
        $params = $_POST;

        $kayttaja = Kayttaja::find($id);
        $nimi = $kayttaja->nimi;
        $taso = $kayttaja->taso;
        $attributes = array(
            'id' => $id,
            'nimi' => $nimi,
            'taso' => $taso,
            'salasana' => $params['salasana'],
            'salasana2' => $params['salasana2']
        );


        $user = new Kayttaja($attributes);

        $errors = $user->errors();

        if (count($errors) == 0) {
            $user->password();
            Redirect::to('/kayttajahallinta/' . $user->id . '/edit', array('message' => 'Käyttäjätiliä on muokattu!'));
        } else {
            Redirect::to('/kayttajahallinta/' . $user->id . '/edit', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

}
