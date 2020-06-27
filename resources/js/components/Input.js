import React from 'react';

class InputCurrency extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        let min = this.props.min ? this.props.min : 0;
        return (
            <div className="form-group">
                { this.props.label && <label htmlFor={this.props.name}>{this.props.label}</label> }
                <input id={this.props.name}
                       name={this.props.name}
                       value={this.props.value}
                       min={min}
                       type="number"
                       className="form-control"
                       onChange={this.props.handleInput} />
                       <p id={`${this.props.name}Error`} className="text-danger text-hide">Amount should be natural number greater or equal {min}</p>
            </div>
        );
    }
}

class InputResult extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="form-group">
                { this.props.label && <label htmlFor={this.props.name}>{this.props.label}</label> }
                <input id={this.props.name}
                       name={this.props.name}
                       value={this.props.value}
                       type="text"
                       className="form-control"
                       disabled />
            </div>
        );
    }
}

export {
    InputCurrency,
    InputResult
};
