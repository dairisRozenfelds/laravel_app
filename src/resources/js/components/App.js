import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import $ from 'axios';

import CurrencyRate from './CurrencyRate'
import NavigationItem from './NavigationItem'
import PageItem from './PageItem'

export default class App extends Component {
    constructor(props) {
        super(props);

        this.state = {
            currencyRatePages: [],
            currencyRates: [],
            navigationItems: [],
            pageItems: [],
        };

        this.onCurrencyRateUpdateSuccess = this.onCurrencyRateUpdateSuccess.bind(this);
        this.getCurrenciesFromCode = this.getCurrenciesFromCode.bind(this);
        this.getPaginationItems = this.getPaginationItems.bind(this);
        this.handlePageClick = this.handlePageClick.bind(this);
        this.formatNavigationArray = this.formatNavigationArray.bind(this);
    }

    componentDidMount() {
        $.get(this.getCurrencyRatesUrl())
            .then(this.onCurrencyRateUpdateSuccess)
            .catch(error => {console.error(error)});

        $.get('api/get-all-currencies')
            .then(response => {
                this.setState({ navigationItems: this.formatNavigationArray(response.data) });
            })
            .catch(error => {console.error(error)});
    }

    render() {
        const currencyRates = this.state.currencyRates.map(currencyRate =>
            <CurrencyRate
                key={currencyRate.id}
                currency={currencyRate.currency}
                rate={currencyRate.rate}
                publishedAt={currencyRate.published_at}
                createdAt={currencyRate.created_at}
                updatedAt={currencyRate.updated_at}/>
        );

        const navigationItems = this.state.navigationItems.map((navigationItem, index) =>
            <NavigationItem
                key={index}
                currency={navigationItem.currency}
                label={navigationItem.label}
                getCurrenciesFromCode={this.getCurrenciesFromCode}/>
        );

        return (
            <div className="App__content">
                <div className="currencyRates">
                    <div className="currencyRates__navigation">
                        {navigationItems}
                    </div>
                    {currencyRates}
                    <div className="Pagination">
                        {this.getPaginationItems()}
                    </div>
                </div>
            </div>
        );
    }

    getPaginationItems() {
        const pagination = [];

        if (Number.isInteger(this.state.currencyRatePages.last_page)) {
            for (let i = 1; i <= this.state.currencyRatePages.last_page; i++) {
                pagination.push(
                    <PageItem
                        key={i}
                        page={i}
                        isActive={i === parseInt(this.state.currencyRatePages.current_page)}
                        handlePageClick={this.handlePageClick}/>
                );
            }
        }

        return pagination;
    }

    formatNavigationArray(data) {
        // Add show all navigation item with the code 'SHOW_ALL'
        data.push({
            currency: this.getShowAllCode(),
            label: 'Parādīt visu'
        });

        return data;
    }

    handlePageClick(page) {
        if (page !== parseInt(this.state.currencyRatePages.current_page)) {
            $.get(this.state.currencyRatePages.path + '?page=' + page)
                .then(this.onCurrencyRateUpdateSuccess)
                .catch(error => {console.error(error)});
        }
    }

    getCurrencyRatesUrl() {
        return 'api/get-currency-rates';
    }

    getShowAllCode() {
        return 'SHOW_ALL';
    }

    getCurrenciesFromCode(code) {
        if (code !== this.getShowAllCode()) {
            $.get(this.getCurrencyRatesUrl() + '/' + code)
                .then(this.onCurrencyRateUpdateSuccess)
                .catch(error => {console.error(error)});
        } else {
            $.get(this.getCurrencyRatesUrl())
                .then(this.onCurrencyRateUpdateSuccess)
                .catch(error => {console.error(error)});
        }
    }

    onCurrencyRateUpdateSuccess(response) {
        this.setState({
            currencyRatePages: response.data,
            currencyRates: response.data.data
        });
    }
}

ReactDOM.render(<App />, document.getElementById('app'));
