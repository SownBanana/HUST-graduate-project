import { createAsyncThunk, createSlice } from "@reduxjs/toolkit";
import AuthAPI from "../../commons/api/AuthAPI";
import { enqueueSnackbar } from "../Toast/toastSlices";

export const logout = createAsyncThunk("auth/logout", async () => {
	try {
		await AuthAPI.logout();
	} catch (e) {
		console.log(e);
	}
});

export const register = createAsyncThunk("auth/register", async (params) => {
	try {
		console.log("params", params);
		const response = await AuthAPI.register(params);
		console.log(response);
		return response.json();
	} catch (e) {
		console.log(e);
	}
});
export const login = createAsyncThunk("auth/login", async (params) => {
	try {
		const response = await AuthAPI.login(params);
		return response.json();
	} catch (e) {
		console.log(e);
	}
});

export const accessTokenExpired = (refresh_token) =>
	async function getNewToken(dispatch) {
		console.log("getting new token");
		console.log(refresh_token);
		try {
			const response = await AuthAPI.refreshToken(refresh_token);
			const data = await response.json();
			if (data.status === "success") dispatch(authSuccess(data));
			else dispatch(authFail());
		} catch (e) {
			console.log(e);
		}
	};

export const resendVerify = (email) =>
	async function resendEmail(dispatch) {
		console.log(email);
		try {
			const response = await AuthAPI.resendEmail(email);
			const data = await response.json();
			const key = new Date().getTime() + Math.random();
			dispatch(
				enqueueSnackbar({
					key: key,
					message: data.mss,
					options: {
						key: key,
						// preventDuplicate: true,
						variant: data.status === "success" ? "success" : "error",
						autoHideDuration: 2000,
						anchorOrigin: {
							vertical: "bottom",
							horizontal: "left",
						},
					},
				})
			);
		} catch (e) {
			console.log(e);
		}
	};

export const checkPassport = () => async (dispatch) => {
	console.log("checking passport");
	const response = await AuthAPI.checkPassport();
	const data = await response.json();
	const key = new Date().getTime() + Math.random();
	dispatch(
		enqueueSnackbar({
			key: key,
			message: "success" in data ? "Authenticated" : "Unauthenticated",
			options: {
				key: key,
				// preventDuplicate: true,
				variant: "success" in data ? "success" : "error",
				autoHideDuration: 2000,
				anchorOrigin: {
					vertical: "bottom",
					horizontal: "left",
				},
			},
		})
	);
};

function clearError(state) {
	state.error.wrongLogin = false;
	state.error.wrongPass = false;
	state.error.notConfirm = "";
	state.error.usedUsername = false;
	state.error.usedEmail = false;
}

function setFail(state) {
	state.isLoggedIn = false;
	state.access_token = null;
	state.refresh_token = null;
	state.expires_on = 0;
	clearError(state);
}
function setSuccess(
	state,
	{ access_token, refresh_token, expires_on, expires_in }
) {
	state.access_token = access_token;
	state.refresh_token = refresh_token;
	state.expires_on = expires_on
		? expires_on
		: Math.floor(Date.now() / 1000) + expires_in - 10;
	state.isLoggedIn = true;
	clearError(state);
}

const initialState = {
	access_token: null,
	refresh_token: null,
	expires_on: 0,
	isLoggedIn: false,
	error: {
		wrongLogin: false,
		wrongPass: false,
		notConfirm: "",
		usedUsername: false,
		usedEmail: false,
	},
};

const auth = createSlice({
	name: "auth",
	initialState,
	reducers: {
		authSuccess: (state, action) => {
			setSuccess(state, action.payload);
			localStorage.setItem("auth", JSON.stringify({ ...state, error: null }));
		},
		authFail: (state) => {
			setFail(state);
			localStorage.setItem("auth", JSON.stringify({ ...state, error: null }));
		},
	},
	extraReducers: {
		[login.fulfilled]: (state, action) => {
			console.log(action.payload);
			if (action.payload.status === "success") {
				setSuccess(state, action.payload);
			} else if (action.payload.status === "notConfirm") {
				setFail(state);
				state.error.notConfirm = action.payload.email;
			} else {
				setFail(state);
				if (action.payload.type === "login") {
					state.error.wrongLogin = true;
				} else {
					state.error.wrongPass = true;
				}
			}
			localStorage.setItem("auth", JSON.stringify({ ...state, error: null }));
		},
		[logout.rejected]: (state) => {
			setFail(state);
			localStorage.setItem("auth", JSON.stringify({ ...state, error: null }));
		},
		[logout.fulfilled]: (state) => {
			setFail(state);
			localStorage.setItem("auth", JSON.stringify({ ...state, error: null }));
		},
	},
});

export const { authSuccess, authFail } = auth.actions;

export default auth.reducer;
