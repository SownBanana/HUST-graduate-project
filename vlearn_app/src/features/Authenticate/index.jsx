import { React } from "react";
import { useDispatch } from "react-redux";
import { Route, Switch, Link } from "react-router-dom";
import Login from "./pages/Login";
import Register from "./pages/Register";
import { logout } from "./authSlices";

export default function Authenticate() {
	const dispatch = useDispatch();
	return (
		<Switch>
			<Route exact path="/auth">
				<h3>Authenticate Portal</h3>
				<Link to={`auth/login`}>Login </Link>
				<Link to={`auth/register`}>Register </Link>
				<a
					onClick={(e) => {
						e.preventDefault();
						dispatch(logout());
					}}
				>
					Logout
				</a>
			</Route>
			<Route path="/auth/login" component={Login} />
			<Route path="/auth/register" component={Register} />
		</Switch>
	);
}
