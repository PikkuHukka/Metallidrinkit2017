<?php

class AinesosaController extends BaseController {

    public static function ainesosat() {
        $ainesosat = Ainesosa::all();
        View::make('ainesosat.html', array('ainesosat' => $ainesosat));
    }

    public static function drinkitAinesosienMukaan($haku) {
        $drinkit = Liitostaulu::haeDrinkit($haku);
        View::make('drinkit.html', array('drinkit' => $drinkit));
    }

}
