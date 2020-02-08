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
     * Regular expression for matching currency code format, for example:
     * EUR, USD, ...
     */
    const CURRENCY_FORMAT_REGEX = '/^[A-Z]{3}$/';

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
                $currencyRates = [];

                // Get the currency code and rate array
                try {
                    $currencyRates = $this->parseCurrencyStringToArray((string)$rssItem->description);
                } catch (Exception $e) {
                    $this->error('There was an error while parsing currency data');
                    $this->error('Reason: ' . $e->getMessage());
                }

                // Get the currency rate's published date
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
                        $this->error('There was an error while importing currency ' . $currency .  '. Published at ' . $publishedAt->format(DateTime::RSS));
                        $this->error('Reason: ' . $e->getMessage());
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
     * @throws Exception
     */
    protected function parseCurrencyStringToArray($currencyString)
    {
        $rawArray = explode(' ', trim($currencyString));
        $resultArray = [];

        foreach ($rawArray as $i => $value) {
            if ($i % 2 === 0) { // Finds the keys (currencies)
                if (!preg_match(self::CURRENCY_FORMAT_REGEX, $value)) {
                    throw new Exception('Wrong currency format');
                }

                $resultArray[$value] = null;
            } else { // Sets the exchange rates to the previous currency
                $resultArray[$rawArray[$i - 1]] = (float)$value;
            }
        }

        return $resultArray;
    }
}
