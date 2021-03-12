import { Button } from "@material-ui/core";
import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { Link } from "react-router-dom";
import { checkPassport } from "../Authenticate/authSlices";

function Home() {
	const dispatch = useDispatch();
	const access_token = useSelector((state) => state.auth.access_token);
	return (
		<div>
			Home
			<Link to="/auth">auth</Link>
			<Button
				onClick={(e) => {
					e.preventDefault();
					// console.log("passport");
					dispatch(checkPassport(access_token));
				}}
			>
				Check Auth
			</Button>
			{/* <Suspense fallback={<div>Loading ...</div>}>
				<Switch>
					<Route exact path="/" component={Home} />
					<Route exact path="/auth" component={Authenticate} />
					<Route exact path={`/auth/login`}>
						<Login />
					</Route>
					<Route exact path="/auth/register/" component={Register} />
				</Switch>
			</Suspense> */}
		</div>
	);
}

export default Home;
