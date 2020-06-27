import React from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import { Select } from './components/Select';
import { InputCurrency, InputResult } from './components/Input';
import { SwitchButton } from './components/Button';

class Index extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            apiUrl: 'http://127.0.0.1:8000/api',
            sourcesList: [],
            sourceError: '',
            choosedSource: "NBP",
            exchangeFromCurrency: "PLN",
            exchangeFromAmount: 1,
            exchangeToCurrency: "EUR",
            currencyConvertResult: 0,
            currenciesList: [],
            currentDate: '',
        };
        this.handleButton = this.handleButton.bind(this);
        this.handleInput = this.handleInput.bind(this);
        this.handleSelect = this.handleSelect.bind(this);
        this.handleSwitchCurrencyButton = this.handleSwitchCurrencyButton.bind(this);
    }

    handleButton() {
        if(this.state.exchangeFromAmount > 0) {
            this.exchangeCurrency();
        }
    }

    getCurrentDate() {
        let date = new Date();
        let year = date.getFullYear();
        let month = date.getMonth() + 1;
        month = month < 10 ? `0${month}` : month;
        let day = date.getDate();
        day = day < 10 ? `0${day}` : day;
        return `${year}-${month}-${day}`;
    }

    exchangeCurrency() {
        const {choosedSource, exchangeFromCurrency, exchangeToCurrency, exchangeFromAmount} = this.state;
        let self = this;
        axios.get(`${this.state.apiUrl}/convert/${choosedSource}/${exchangeFromCurrency}/${exchangeToCurrency}/${exchangeFromAmount}`)
            .then(response => {
                self.setState(() => ({
                    currencyConvertResult: response.data.result,
                    lastUpdate: response.data.lastUpdate
                }));
            })
            .catch(function (error) {
                if (error.response) {
                    // console.log(error.response.data);
                    // console.log(error.response.status);
                    // console.log(error.response.headers);

                    // self.setState(() => ({
                    //     currencyConvertResult: error.response.data.message
                    // }));
                }
            });
    }

    handleInput(event) {
        const name = event.target.name;
        const value = parseInt(event.target.value);
        this.inputNumberError(name, value, event.target.min);
        event.target.value = value;
        this.setState(() => ({
            [name]: value
        }));
    }

    inputNumberError(name, value, min) {
        const errorInfoElement = $('#' + name + 'Error');
        if(value >= min) {
            $(errorInfoElement).addClass('text-hide');
        }
        else {
            $(errorInfoElement).removeClass('text-hide');
        }
    }

    handleSelect(event) {
        const name = event.target.name;
        const value = event.target.value;
        this.setState(() => ({
            [name]: value
        }));
    }

    getSourcesList() {
        let self = this;
        axios.get(`${this.state.apiUrl}/sources-list`)
            .then(response => {
                console.log('getSourcesList', response.data.list);
                const sourcesList = response.data.list.map((source, index) => <option key={index} val={source.name}>{source.name}</option>);
                self.setState(() => ({
                    sourcesList: sourcesList
                }));
            })
            .catch(function (error) {
                if (error.response) {
                    // console.log(error.response.data);
                    // console.log(error.response.status);
                    // console.log(error.response.headers);
                }
            });
    }

    getCurrencyList() {
        let self = this;
        axios.get(`${this.state.apiUrl}/currencies-list/${this.state.choosedSource}`)
            .then(response => {
                // console.log('getCurrencyList', response.data.list);
                const currenciesList = response.data.list.map((currency, index) => <option key={index} val={currency.code}>{currency.code}</option>);
                self.setState(() => ({
                    currenciesList: currenciesList,
                    sourceError: ''
                }));
            })
            .catch(function (error) {
                if (error.response) {
                    self.setState(() => ({
                        sourceError: error.response.data.message
                    }));
                    // console.log(error.response.data);
                    // console.log(error.response.status);
                    // console.log(error.response.headers);
                }
            });
    }

    handleSwitchCurrencyButton() {
        let oldState = this.state;
        this.setState(() => ({
            exchangeFromCurrency: oldState.exchangeToCurrency,
            exchangeToCurrency: oldState.exchangeFromCurrency
        }));
    }

    componentDidMount() {
        this.getSourcesList();
        this.getCurrencyList();
        this.exchangeCurrency();
        this.setState(() => ({
            currentDate: this.getCurrentDate()
        }));
    }

    render() {
        return (
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-md-8">
                        <div className="card">
                            <div className="card-header">Currency Exchange App</div>
                            <div className="card-body">
                                <div className="row">
                                    <div className="col">
                                        <Select value={this.state.choosedSource}
                                                message={this.state.sourceError}
                                                options={this.state.sourcesList}
                                                lastUpdate={this.state.lastUpdate}
                                                currentDate={this.state.currentDate}
                                                name="choosedSource"
                                                label="Source"
                                                handleSelect={this.handleSelect} />
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-sm-5 col-md-4 col-lg-5">
                                        <Select value={this.state.exchangeFromCurrency}
                                                options={this.state.currenciesList}
                                                name="exchangeFromCurrency"
                                                label="From"
                                                handleSelect={this.handleSelect} />
                                    </div>
                                    <div className='col-sm-2 col-md-4 col-lg-2'>
                                        <SwitchButton name="switchButton"
                                                      label="Switch"
                                                      handleSwitchCurrencyButton={this.handleSwitchCurrencyButton} />
                                    </div>
                                    <div className="col-sm-5 col-md-4 col-lg-5">
                                        <Select value={this.state.exchangeToCurrency}
                                                options={this.state.currenciesList}
                                                name="exchangeToCurrency"
                                                label="To"
                                                handleSelect={this.handleSelect} />
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col">
                                        <InputCurrency value={this.state.exchangeFromAmount}
                                                       name="exchangeFromAmount"
                                                       label="Amount"
                                                       min="1"
                                                       handleInput={this.handleInput}
                                                       classes="mt-3" />
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col">
                                        <InputResult value={this.state.currencyConvertResult}
                                                     name="currencyConvertResult"
                                                     label="Result"
                                                     classes="mt-3" />
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col">
                                        <button type="button" onClick={this.handleButton} className="btn btn-block btn-outline-success">Convert</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default Index;

if (document.getElementById('app')) {
    ReactDOM.render(<Index />, document.getElementById('app'));
}
