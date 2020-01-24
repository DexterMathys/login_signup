import React from 'react';

/**
 * Login class
 */
export default class Login extends React.Component {
	/**
	 * Constructor.
	 * @param {any} props porps.
	 */
	constructor(props) {
        super(props);
        
        this.onChangeAttribute = this.onChangeAttribute.bind(this);

		this.state = {
            email: '',
            password: '',
            autenticate: ''
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
            email: last_username,
            autenticate: autenticate
        });
	}

	/**
	 * Constructor.
	 * @return {any} html.
	 */
	render() {
        let messageAlert = (error) ? <div className="alert alert-danger">{error.message}</div> : '';

		// return HTML
		return (
            <div className="wrap-login100">
                <form className="login100-form validate-form" method="post">
                    <span className="login100-form-logo">
                        <i className="fab fa-sellsy"></i>
                    </span>

                    <span className="login100-form-title p-b-34 p-t-27">
                        Login
                    </span>
                    {messageAlert}

                    <div className="wrap-input100 validate-input" data-validate = "Enter email">
                        <input 
                            type="text" 
                            value={this.state.email} 
                            name="email" 
                            onChange={this.onChangeAttribute} 
                            id="inputEmail" 
                            className="input100" 
                            placeholder="Email"  
                        />
                        <span className="focus-input100" data-placeholder="&#xf207;"></span>
                    </div>

                    <div className="wrap-input100 validate-input" data-validate="Enter password">
                        <input 
                            type="password" 
                            value={this.state.password}
                            name="password" 
                            onChange={this.onChangeAttribute} 
                            id="inputPassword" 
                            className="input100" 
                            placeholder="Password"  
                        />
                        <span className="focus-input100" data-placeholder="&#xf191;"></span>
                    </div>

                    <input type="hidden" name="_csrf_token" value={this.state.autenticate} />

                    <div className="container-login100-form-btn">
                        <button className="login100-form-btn">
                            Log in
                        </button>
                    </div>

                    <div className="text-center p-t-90">
                        <p>
                            <a className="txt1" href="#">
                                Forgot Password?
                            </a>
                        </p>
                        <p>
                            <a className="txt1" href="#">
                                Don't have an account? Sign up
                            </a>
                        </p>
                    </div>
                </form>
            </div>
		);
	}
}
