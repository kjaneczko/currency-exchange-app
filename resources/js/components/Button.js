import React from 'react';

class SwitchButton extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="form-group">
                { this.props.label && <label htmlFor={this.props.name}>{this.props.label}</label> }
                <button id={this.props.name}
                        type="button"
                        className="btn btn-block btn-outline-primary"
                        onClick={this.props.handleSwitchCurrencyButton}>
                    {`<->`}
                </button>
            </div>
        );
    }
}

export {
    SwitchButton
};
