<?php

Route::get('/', 'CurrencyRateController@index');

Route::prefix('api')->group(function () {
    Route::get('get-all-currencies', 'CurrencyRateController@getAllCurrencies');
    Route::get('get-currency-rates', 'CurrencyRateController@getCurrencyRates');
    Route::get('get-currency-rates/{code}', 'CurrencyRateController@getCurrencyRatesFromCode');
});
