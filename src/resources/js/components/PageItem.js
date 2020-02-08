import React, { Component } from 'react';

class PageItem extends Component {
    constructor(props) {
        super(props);

        this.handlePageClick = this.handlePageClick.bind(this);
    }

    render() {
        return (
            <div className={'Pagination__pageItem' + (this.props.isActive ? ' Pagination__pageItem--active' : '')} onClick={this.handlePageClick}>
                <span className="Pagination__pageItem__text">{this.props.page}</span>
            </div>
        );
    }

    handlePageClick() {
        this.props.handlePageClick(this.props.page);
    }
}

export default PageItem;
