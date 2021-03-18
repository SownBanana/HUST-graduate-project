import "./assets/styles/App.scss";
import React from "react";
import { Route, Switch } from "react-router-dom";
import PrivateRoute from "./commons/PrivateRoute";
import Home from "./features/Home";
import { Suspense } from "react";
import { useDispatch } from "react-redux";
import { authSuccess } from "./features/Authenticate/authSlices";
import Toast from "./features/Toast";
import Notifier from './Notifier';
const Authenticate = React.lazy(() => import("./features/Authenticate/"));

function App() {
	const dispatch = useDispatch();
	if (
		localStorage.getItem("auth") &&
		JSON.parse(localStorage.getItem("auth")).isLoggedIn
	)
		dispatch(authSuccess(JSON.parse(localStorage.getItem("auth"))));
	return (
		<div className="App">
			<Toast />
			<Notifier />
			<Suspense fallback={<div>Loading ...</div>}>
				<Switch>
					<Route path="/auth" component={Authenticate} />
					<PrivateRoute exact path="/" component={Home} />
				</Switch>
			</Suspense>
		</div>
	);
}

export default App;
