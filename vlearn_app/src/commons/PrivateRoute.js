import React from "react";
import { useSelector } from "react-redux";
import { Redirect, Route } from "react-router-dom";

export default function PrivateRoute({ component: Component, ...rest }) {
	const isAuthed = useSelector((state) => state.auth.access_token);
	return (
		<Route
			{...rest}
			exact
			render={(props) =>
				isAuthed ? (
					<div>{React.createElement(Component, props)}</div>
				) : (
					<Redirect
						to={{
							pathname: "/auth/login",
							state: { from: props.location },
						}}
					/>
				)
			}
		/>
	);
}
