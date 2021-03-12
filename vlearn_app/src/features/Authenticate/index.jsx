import { React } from "react";
import {
	Route,
	Switch,
	useRouteMatch,
	Link,
} from "react-router-dom";
// import Login from "./pages/Login";
// import Register from "./pages/Register";

export default function Authenticate() {
	const { path } = useRouteMatch();
	return (
		<div>
			{/* <Router> */}
			<Switch>
				<Route exact path={path}>
					<h3>Authenticate Portal</h3>
					<Link to={`${path}/login`}>Login</Link>
					<Link to={`${path}/register`}>Register</Link>
				</Route>
				{/* <Route path={`${path}/login`}>
						<Login path={path} />
					</Route>
					<Route path={`${path}/register`} component={Register} /> */}
			</Switch>
			{/* </Router> */}
		</div>
	);
}
