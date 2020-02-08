import React, { Component } from 'react';

class NavigationItem extends Component {
    constructor(props) {
        super(props);

        this.renderCurrencies = this.renderCurrencies.bind(this);
    }

    render() {
        return (
            <a className="NavigationItem" onClick={this.renderCurrencies}>
                <div className="NavigationItem__currency">
                    <span>{this.props.label ? this.props.label : this.props.currency}</span>
                </div>
            </a>
        );
    }

    renderCurrencies() {
        this.props.getCurrenciesFromCode(this.props.currency);
    }
}

export default NavigationItem;
