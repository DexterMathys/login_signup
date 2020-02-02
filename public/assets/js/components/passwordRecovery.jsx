import React from 'react';

/**
 * PasswordRecovery class
 */
export default class PasswordRecovery extends React.Component {
	/**
	 * Constructor.
	 * @param {any} props porps.
	 */
	constructor(props) {
        super(props);
        
        this.onChangeAttribute = this.onChangeAttribute.bind(this);

		this.state = {
            _password1: '',
            _password2: '',
            _recovery: ''
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
		// this.setState({
        //     email: last_username,
        //     autenticate: autenticate
        // });
	}

	/**
	 * Constructor.
	 * @return {any} html.
	 */
	render() {
        let recoveryForm = '';
        let errorMessage = '';

        if (error && error.message != '' && error.action != 'recovery') {
            errorMessage = <div className={"alert alert-block " + error.alert}>
                                <button type="button" className="close" data-dismiss="alert"><i className="fa fa-close"></i></button>
                                <p>{error.message}</p>
                            </div>;
        }

        switch (error.status) {
            case '2':
                recoveryForm = <form id="paaswordRecovery" action={newPasswordUrl} method="post" className="login100-form validate-form">
                                    <span className="login100-form-logo"><i className="fab fa-sellsy"></i></span>
                                    <span className="login100-form-title p-b-34 p-t-27">New password</span>

                                    {errorMessage}

                                    <input type="hidden" id="username" name="_username" value={error.username} />

                                    <div className="wrap-input100 validate-input" data-validate="Enter email">
                                        <input type="text" disabled="disabled" className="input100" value={error.username} />
                                        <span className="focus-input100" data-placeholder=""></span>
                                    </div>
                                    <div className="wrap-input100 validate-input" data-validate="Enter password">
                                        <input 
                                            type="password" 
                                            id="password1" 
                                            name="_password1" 
                                            className="input100" 
                                            placeholder="New password" 
                                            value={this.state._password1}
                                            onChange={this.onChangeAttribute} 
                                        />
                                        <span className="focus-input100" data-placeholder=""></span>
                                    </div>
                                    <div className="wrap-input100 validate-input" data-validate="Enter password">
                                        <input 
                                            type="password" 
                                            id="password2" 
                                            name="_password2" 
                                            className="input100" 
                                            placeholder="Repeat password" 
                                            value={this.state._password2}
                                            onChange={this.onChangeAttribute}
                                        />
                                        <span className="focus-input100" data-placeholder=""></span>
                                    </div>
                                    
                                    <div className="container-login100-form-btn">
                                        <button className="login100-form-btn">Modify password</button>
                                    </div>

                                    <div className="text-center p-t-90">
                                        <p>
                                            <a className="txt1" href={loginUrl}>Back to login</a>
                                        </p>
                                    </div>
                                </form>;
                break;
            case '3':
            case '1':
                let titleForm = '';
                switch (error.status) {
                    case '1':
                        titleForm = 'Mail sent';
                        break;
                    case '2':
                        titleForm = 'Error';
                        break;
                    case '3':
                        titleForm = 'New Password';
                        break;
                    default:
                        titleForm = '';
                        break;
                }

                recoveryForm = <div id="paaswordRecovery" name="paaswordRecovery" action={passwordRecoveryUrl} method="post">
                                    <span className="login100-form-logo"><i className="fab fa-sellsy"></i></span>
                                    <span className="login100-form-title p-b-34 p-t-27">
                                        {titleForm}
                                    </span>
                                    
                                    {errorMessage}

                                    <div className="container-login100-form-btn">
                                        <a className="login100-form-btn" href={loginUrl}>Back to login</a>
                                    </div>
                                </div>;
                break;
            default:
                recoveryForm = <form id="paaswordRecovery" name="paaswordRecovery" action={passwordRecoveryUrl} method="post" className="login100-form validate-form">
                                    <span className="login100-form-logo"><i className="fab fa-sellsy"></i></span>
                                    <span className="login100-form-title p-b-34 p-t-27">Password recovery</span>
                                    {errorMessage}

                                    <div className="wrap-input100 validate-input" data-validate="Enter email">
                                        <input 
                                            type="text" 
                                            name="_recovery" 
                                            id="recovery" 
                                            className="input100" 
                                            placeholder="Email" 
                                            value={this.state._recovery} 
                                            onChange={this.onChangeAttribute}
                                        />
                                        <span className="focus-input100" data-placeholder=""></span>
                                    </div>
                                    <input type="hidden" name="_action" id="action" value="recovery" />

                                    <div className="container-login100-form-btn">
                                        <button className="login100-form-btn">Send me my recovery email</button>
                                    </div>

                                    <div className="text-center p-t-90">
                                        <p>
                                            <a className="txt1" href={loginUrl}>Back to login</a>
                                        </p>
                                        <p>
                                            <a className="txt1" href={signupUrl}>Don't have an account? Sign up</a>
                                        </p>
                                    </div>
                                </form>;
                break;
        }

		// return HTML
		return (
            <div className="wrap-login100">
                {recoveryForm}
            </div>
		);
	}
}
