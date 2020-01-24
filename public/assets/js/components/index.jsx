import React from 'react';

/**
 * Index class
 */
export default class Index extends React.Component {
	/**
	 * Constructor.
	 * @param {any} props porps.
	 */
	constructor(props) {
        super(props);
        
        this.onChangeAttribute = this.onChangeAttribute.bind(this);

		this.state = {
            user: {
                username: ''
            }
		};
    }
    
    /**
	 * Manage inputs change
	 * @param {Event} e
	 */
	onChangeAttribute(e) {
		if (!e.target.validity.valid) {
			return;
		}
		this.setState({[e.target.name]: e.target.value});
	}

	/**
	 * componentDidMount() is invoked immediately after a component is mounted (inserted into the tree).
     * Initialization that requires DOM nodes should go here. 
     * If you need to load data from a remote endpoint, this is a good place to instantiate the network request.
	 */
	componentDidMount() {
		this.setState({
            user: user
        });
	}

	/**
	 * Constructor.
	 * @return {any} html.
	 */
	render() {
		// return HTML
		return (
            <div class="container-fluid">
                <h5>Welcome, you are logged in as {this.state.user.username}</h5>
            </div>
		);
	}
}
