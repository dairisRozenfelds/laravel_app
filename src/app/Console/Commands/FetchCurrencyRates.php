<?php

namespace App\Console\Commands;

use App\CurrencyRate;
use DateTime;
use Exception;
use Illuminate\Console\Command;
use function simplexml_load_file;

class FetchCurrencyRates extends Command
{
    /**
     * URL constant from where the currency exchange rates will be fetched from
     */
    const FETCH_URL = 'https://www.bank.lv/vk/ecb_rss.xml';

    /**
     * {@inheritdoc}
     */
    protected $signature = 'currency:fetch';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Fetches the currency exchange rates from the bank.lv RSS feed and inserts it the database';

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        $feed = simplexml_load_file(self::FETCH_URL);

        if ($feed) {
            foreach ($feed->channel->item as $rssItem) {
                $currencyRates = $this->parseCurrencyStringToArray((string)$rssItem->description);
                $publishedAt = DateTime::createFromFormat(DateTime::RSS, $rssItem->pubDate);

                // Save each of the currency in the database
                foreach ($currencyRates as $currency => $rate) {
                    try {
                        /** @var CurrencyRate $currencyRate */
                        $currencyRate = CurrencyRate::whereDate('published_at', $publishedAt)
                            ->where('currency', '=', $currency)
                            ->first();

                        if (!$currencyRate) {
                            $currencyRate = new CurrencyRate();
                        }

                        $currencyRate->setCurrency($currency);
                        $currencyRate->setRate($rate);
                        $currencyRate->setPublishedAt($publishedAt);

                        $currencyRate->save();
                    } catch (Exception $e) {
                        $this->error('There was an error importing currency ' . $currency .  '. Published at ' . $publishedAt->format(DateTime::RSS));
                    }
                }
            }
        }
    }

    /**
     * Parses the currency string provided from RSS feed in the following format:
     * ['AUD' => 1.62990000, 'BGN' => 1.95580000]
     *
     * @param $currencyString
     * @return array
     */
    protected function parseCurrencyStringToArray($currencyString)
    {
        $rawArray = explode(' ', trim($currencyString));
        $resultArray = [];

        foreach ($rawArray as $i => $value) {
            if ($i % 2 === 0) { // Finds the keys (currencies)
                $resultArray[$value] = null;
            } else { // Sets the exchange rates to the previous currency
                $resultArray[$rawArray[$i - 1]] = (float)$value;
            }
        }

        return $resultArray;
    }
}
