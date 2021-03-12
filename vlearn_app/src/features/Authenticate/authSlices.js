import { createSlice } from "@reduxjs/toolkit";
import updateOptions from "../../commons/FetchCommon";
const initialState = {
	access_token: null,
	refresh_token: null,
	expires_in: 0,
	isLoggedIn: false,
};

const auth = createSlice({
	name: "auth",
	initialState,
	reducers: {
		authSuccess: (state, action) => {
			console.log("Dang nhap thanh cong");
			state.access_token = action.payload.access_token;
			state.refresh_token = action.payload.refresh_token;
			state.expires_in =
				Math.floor(Date.now() / 1000) + action.payload.expires_in - 10;
			state.isLoggedIn = true;
			localStorage.setItem("auth", JSON.stringify(state));
		},
		authFail: (state, action) => {
			state.isLoggedIn = false;
			state.access_token = null;
			state.refresh_token = null;
			state.expires_in = 0;
			let a = JSON.stringify(state);
			localStorage.setItem("auth", JSON.stringify(state));
		},
		refreshSuccess: (state, action) => {
			state.token = action.payload.token;
			state.refreshToken = action.payload.refreshToken;
			localStorage.setItem("auth", JSON.stringify(state));
		},
		refreshFail: (state, action) => {
			state.isLoggedIn = false;
			state.access_token = null;
			state.refresh_token = null;
			state.expires_in = 0;
			localStorage.setItem("auth", JSON.stringify(state));
		},
	},
});

export const logout = () => async (dispatch) => {
	console.log("Log out");
	const settings = {
		method: "POST",
		headers: {
			Accept: "application/json",
			"Content-Type": "application/json",
			"Access-Control-Allow-Origin": "http://localhost:3000",
			"Access-Control-Allow-Credentials": "true",
		},
	};
	try {
		const response = await fetch(
			"http://localhost:8088/api/logout",
			updateOptions(settings)
		);
		console.log(response);
		dispatch(authFail());
	} catch (e) {
		console.log(e);
	}
};

export const login = (login, password) => async (dispatch) => {
	console.log("Logining");
	console.log(login, password);
	const settings = {
		method: "POST",
		headers: {
			Accept: "application/json",
			"Content-Type": "application/json",
			"Access-Control-Allow-Origin": "http://localhost:3000",
			"Access-Control-Allow-Credentials": "true",
		},
		body: JSON.stringify({
			login,
			password,
		}),
	};
	try {
		const response = await fetch("http://localhost:8088/api/login", settings);
		console.log(response);
		const data = await response.json();
		if (data.status === "success") dispatch(authSuccess(data));
		else dispatch(authFail(data));
	} catch (e) {
		console.log(e);
	}
};
export const accessTokenExpired = (refresh_token) => async (dispatch) => {
	console.log("getting token");
	console.log(refresh_token);
	const settings = {
		method: "POST",
		headers: {
			Accept: "application/json",
			"Content-Type": "application/json",
			"Access-Control-Allow-Origin": "http://localhost:3000",
			"Access-Control-Allow-Credentials": "true",
		},
		body: JSON.stringify({
			refresh_token,
		}),
	};
	try {
		const response = await fetch(
			"http://localhost:8088/api/auth/refresh",
			settings
		);
		console.log(response);
		const data = await response.json();
		if (data.status === "success") dispatch(authSuccess(data));
		else dispatch(authFail(data));
	} catch (e) {
		console.log(e);
	}
};
export const checkPassport = (access_token) => async (dispatch) => {
	console.log("checking passport");
	const settings = {
		method: "GET",
		headers: {
			Accept: "application/json",
			"Content-Type": "application/json",
			"Access-Control-Allow-Origin": "http://localhost:3000",
			"Access-Control-Allow-Credentials": "true",
			Authorization: "Bearer " + access_token,
		},
	};
	try {
		const response = await fetch(
			"http://localhost:8088/api/check-passport",
			settings
		);
		console.log(response);
		const data = await response.json();
		console.log(data);
	} catch (e) {
		console.log(e);
	}
};

export const {
	authSuccess,
	authFail,
	refreshSuccess,
	refreshFail,
} = auth.actions;

export default auth.reducer;
