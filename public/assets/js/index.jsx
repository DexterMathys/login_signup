import Navbar from './components/navbar.jsx';
import Index from './components/index.jsx';

// Render HTML
import React from 'react';
import ReactDOM from 'react-dom';

ReactDOM.render(
	<Navbar/>,
	document.getElementById('navbar-div')
);

ReactDOM.render(
	<Index/>,
	document.getElementById('index-div')
);
