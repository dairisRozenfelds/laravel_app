import React, { Component } from 'react';

class CurrencyRate extends Component {
    render() {
        return (
            <div className="CurrencyRate">
                <div className="CurrencyRate__leftBlock">
                    <div className="CurrencyRate__currency">
                        <span className="currencyLabel">Valūta:</span><span className="currencyValue">{this.props.currency}</span>
                    </div>
                </div>
                <div className="CurrencyRate__rightBlock">
                    <div className="CurrencyRate__rate">
                        <span className="currencyLabel">Kurss:</span><span className="currencyValue">{parseFloat(this.props.rate).toFixed(8)}</span>
                    </div>
                    <div className="CurrencyRate__publishedAt">
                        <span className="currencyLabel">Publicēts:</span><span className="currencyValue">{this.props.publishedAt}</span>
                    </div>
                </div>
            </div>
        );
    }
}

export default CurrencyRate;
