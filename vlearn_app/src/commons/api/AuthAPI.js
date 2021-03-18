import { addAuthHeader, addBody } from "../FetchCommon";

const settingsPost = {
	method: "POST",
	headers: {
		Accept: "application/json",
		"Content-Type": "application/json",
		"Access-Control-Allow-Origin": "http://localhost:3000",
		"Access-Control-Allow-Credentials": "true",
	},
};
const settingsGet = {
	method: "GET",
	headers: {
		Accept: "application/json",
		"Content-Type": "application/json",
		"Access-Control-Allow-Origin": "http://localhost:3000",
		"Access-Control-Allow-Credentials": "true",
	},
};

const authAPI = {
	register: ({ name, username, email, password }) => {
		const settings = addBody(settingsPost, {
			name,
			username,
			email,
			password,
		});
		try {
			return fetch(
				`${process.env.REACT_APP_BACKEND_URL}/api/register`,
				settings
			);
		} catch (e) {
			throw e;
		}
	},
	login: ({ login, password }) => {
		const settings = addBody(settingsPost, {
			login,
			password,
		});
		try {
			return fetch(`${process.env.REACT_APP_BACKEND_URL}/api/login`, settings);
		} catch (e) {
			throw e;
		}
	},
	logout: () => {
		const settings = addAuthHeader(settingsPost);
		try {
			return fetch(`${process.env.REACT_APP_BACKEND_URL}/api/logout`, settings);
		} catch (e) {
			throw e;
		}
	},

	refreshToken: (refresh_token) => {
		const settings = addBody(settingsPost, {
			refresh_token,
		});
		try {
			return fetch(
				`${process.env.REACT_APP_BACKEND_URL}/api/auth/refresh`,
				settings
			);
		} catch (e) {
			throw e;
		}
	},

	resendEmail: (email) => {
		const settings = addBody(settingsPost, {
			email,
		});
		try {
			return fetch(
				`${process.env.REACT_APP_BACKEND_URL}/api/resend-confirm`,
				settings
			);
		} catch (e) {
			throw e;
		}
	},
	checkPassport: () => {
		const settings = addAuthHeader(settingsGet);
		try {
			return fetch(
				`${process.env.REACT_APP_BACKEND_URL}/api/check-passport`,
				settings
			);
		} catch (e) {
			throw e;
		}
	},
};

export default authAPI;
