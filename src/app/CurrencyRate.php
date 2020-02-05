<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CurrencyRate
 * @package App
 * @property string $currency
 * @property float $rate
 * @property DateTime $published_at
 */
class CurrencyRate extends Model
{
    /**
     * @var string
     */
    protected $table = 'currency_rates';

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return CurrencyRate
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     * @return CurrencyRate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getPublishedAt()
    {
        return $this->published_at;
    }

    /**
     * @param DateTime|string $published_at
     * @return CurrencyRate
     */
    public function setPublishedAt($published_at)
    {
        $this->published_at = $published_at;

        return $this;
    }
}
