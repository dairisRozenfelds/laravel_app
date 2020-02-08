<?php

namespace App\Http\Controllers;

use App\CurrencyRate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CurrencyRateController extends Controller
{
    const PAGE_ITEM_COUNT = 20;

    /**
     * @return Factory|View
     */
    public function index()
    {
        return view('index');
    }

    /**
     * @return JsonResponse
     */
    public function getCurrencyRates()
    {
        return CurrencyRate::orderBy('published_at')->paginate(self::PAGE_ITEM_COUNT);
    }

    /**
     * @return JsonResponse
     */
    public function getAllCurrencies()
    {
        $currencies = DB::table(CurrencyRate::TABLE_NAME)
            ->select('currency')
            ->groupBy('currency')
            ->get();

        return response()->json($currencies);
    }

    /**
     * @param string $code
     * @return mixed
     */
    public function getCurrencyRatesFromCode($code)
    {
        return CurrencyRate::where('currency', '=', $code)
            ->orderBy('published_at')
            ->paginate(self::PAGE_ITEM_COUNT);
    }
}
