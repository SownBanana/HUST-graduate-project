import "./assets/styles/App.scss";
import React from "react";
import { Route, Switch } from "react-router-dom";
import PrivateRoute from "./commons/PrivateRoute";
import Home from "./features/Home";
import { Suspense } from "react";
import Login from "./features/Authenticate/pages/Login/";
import Register from "./features/Authenticate/pages/Register/";
// import axios from "axios";

const Authenticate = React.lazy(() => import("./features/Authenticate/"));
// axios.defaults.headers.post["Accept"] = "application/json";
function App() {
	return (
		<div className="App">
			{/* <Home /> */}
			<Suspense fallback={<div>Loading ...</div>}>
				<Switch>
					<Route exact path="/auth" component={Authenticate} />
					<Route exact path={`/auth/login`}>
						<Login />
					</Route>
					<Route exact path="/auth/register/" component={Register} />
					<PrivateRoute exact path="/" component={Home} />
				</Switch>
			</Suspense>
		</div>
	);
}

export default App;
