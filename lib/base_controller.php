<?php

class BaseController {

    public static function get_user_logged_admin() {
        if (isset($_SESSION['user'])) {

            $user_id = $_SESSION['user'];
            $user = Kayttaja::find($user_id);
            $taso = Kayttaja::returnTaso($user_id);
            if ($taso == 'admin') {
                return $user;
            }
            //Ei ole admin
            return null;
        }
    //Ei ole kirjautunut sisään
        return null;
    }

    public static function get_user_logged_in() {
        if (isset($_SESSION['user'])) {
            $user_id = $_SESSION['user'];
            $user = Kayttaja::find($user_id);

            return $user;
        }

        return null;
    }

    public static function get_user_logged_in_name() {
        if (isset($_SESSION['user'])) {
            $user_id = $_SESSION['user'];
            $user = Kayttaja::returnName($user_id);

            return $user;
        }

        return null;
    }

    public static function check_logged_in() {
        if (!isset($_SESSION['user'])) {
            Redirect::to('/login', array('error' => 'Kirjaudu ensin sisään!'));
        }
    }

    public static function check_logged_out() {
        if (isset($_SESSION['user'])) {
            Redirect::to('/', array('error' => 'Kirjaudu ensin ulos!'));
        }
    }

    public static function check_logged_admin() {
        if (!isset($_SESSION['user'])) {
            Redirect::to('/login', array('error' => 'Kirjaudu ensin sisään!'));
        }
        $user_id = $_SESSION['user'];
        $taso = Kayttaja::returnTaso($user_id);
        if ($taso != 'admin') {
            Redirect::to('/', array('error' => 'Tälle sivulle vaaditaan admin tunnukset!'));
        }
    }

}
