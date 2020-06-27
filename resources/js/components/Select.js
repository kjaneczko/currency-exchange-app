import React from 'react';

class Select extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        let outdated = this.props.lastUpdate && (this.props.lastUpdate < this.props.currentDate) ? true : false;
        return (
            <div className="form-group">
                { this.props.label &&
                    <label htmlFor={this.props.name} className={outdated ? 'text-danger' : ''}>
                        {this.props.label}
                        {this.props.lastUpdate && ` (Last update: ${this.props.lastUpdate})`}
                        {outdated && ` OUTDATED`}
                    </label>
                }
                <select id={this.props.name}
                        name={this.props.name}
                        className={`form-control ` + (this.props.message || outdated && `is-invalid`)}
                        value={this.props.value}
                        onChange={this.props.handleSelect}>
                    {this.props.options}
                </select>
                { this.props.message && <p className="text-danger">{this.props.message}</p> }
            </div>
        );
    }
}

export {
    Select
};
