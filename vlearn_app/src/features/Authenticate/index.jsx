import { React, useEffect, useState } from "react";
import { useDispatch } from "react-redux";
import { Route, Switch, Link } from "react-router-dom";
import Login from "./pages/Login";
import Register from "./pages/Register";
import { logout } from "./authSlices";
import Google from "./pages/OAuth/Google";
import api from "../../commons/api/AuthAPI";
import { setPreviousURL } from "../../commons/SliceCommon";

export default function Authenticate() {
	const dispatch = useDispatch();
	const [googleURL, setGoogleURL] = useState(null);
	dispatch(setPreviousURL("/auth"));

	useEffect(() => {
		const data = api.getSocialURL("google");
		data.then(function (result) {
			if (result) {
				setGoogleURL(result.url);
			}
		});
		return () => {};
	}, []);

	return (
		<Switch>
			<Route exact path="/auth">
				<h3>Authenticate Portal</h3>
				<Link to={`auth/login`}>Login </Link>
				<Link to={`auth/register`}>Register </Link>
				<a href={googleURL}>Google </a>
				<button
					onClick={(e) => {
						e.preventDefault();
						dispatch(logout());
					}}
				>
					Logout
				</button>
			</Route>
			<Route path="/auth/login" component={Login} />
			<Route path="/auth/register" component={Register} />
			<Route path="/auth/google" component={Google} />
		</Switch>
	);
}
