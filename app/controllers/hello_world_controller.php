<?php


class HelloWorldController extends BaseController {

    public static function index() {
        View::make('home.html');
    }

    public static function sandbox() {
        $drinkit = Drinkki::all();
        $kapteeni = Drinkki::find(1);

        // Kint-luokan dump-metodi tulostaa muuttujan arvon
        Kint::dump($drinkit);
        Kint::dump($kapteeni);
    }

    public static function login() {
        View::make('login.html');
    }

    public static function drinkit() {
        View::make('drinkit.html');
    }

    public static function esittely() {
        View::make('esittely.html');
    }

    public static function lisays() {
        View::make('lisays.html');
    }

}
