import { VerticalAlignBottom } from "@material-ui/icons";
import { accessTokenExpired } from "../features/Authenticate/authSlices";
var waitingRefresh = false;
export default (store) => (next) => (action) => {
	if (typeof action === "function") {
		console.log("=================MDW===============");
		console.log(action);
		const { dispatch } = store;
		const state = store.getState();
		const isLoggedIn = state.auth.isLoggedIn;
		const expires_in = state.auth.expires_in;
		const refresh_token = state.auth.refresh_token;
		console.log(Math.floor(Date.now() / 1000), expires_in, isLoggedIn);
		if (
			isLoggedIn &&
			Math.floor(Date.now() / 1000) > expires_in &&
			!waitingRefresh
		) {
			waitingRefresh = true;
			dispatch(accessTokenExpired(refresh_token));
			waitingRefresh = false;
		}
	}
	return next(action);
};
