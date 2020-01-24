import React from 'react';

/**
 * Navbar class
 */
export default class Navbar extends React.Component {
	/**
	 * Constructor.
	 * @param {any} props porps.
	 */
	constructor(props) {
        super(props);
        
        this.onChangeAttribute = this.onChangeAttribute.bind(this);

		this.state = {
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
        });
	}

	/**
	 * Constructor.
	 * @return {any} html.
	 */
	render() {
		// return HTML
		return (
            <nav class="navbar navbar-expand-lg navbar-dark bg-navbar">
                <a class="navbar-brand" href="#">Navbar</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Dropdown
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                    </li>
                    </ul>
                    <form class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" />
                        <a class="login75-form-btn" href={logoutUrl}>Log out</a>
                    </form>
                </div>
            </nav>
		);
	}
}
