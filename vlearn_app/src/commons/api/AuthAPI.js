import { addAuthHeader, addBody } from "../FetchCommon";
import api, { headersWithToken } from "../AxiosCommon";

const authAPI = {
	register: async ({ name, username, email, password }) => {
		const response = await api.post(`/api/register`, {
			name,
			username,
			email,
			password,
		});
		return response.data;
	},
	login: async ({ login, password }) => {
		try {
			let response = await api.post(`/api/login`, {
				login,
				password,
			});
			return response.data;
		} catch (e) {
			if (e.response.status === 401) return e.response.data;
		}
	},
	logout: async () => {
		const response = await api.post(
			`/api/logout`,
			{},
			{
				headers: headersWithToken(),
			}
		);
		return response.data;
	},

	refreshToken: async (refresh_token) => {
		const response = await api.post(`/api/auth/refresh`, {
			refresh_token,
		});
		return response.data;
	},

	resendEmail: async (email) => {
		const response = await api.post(`/api/resend-confirm`, {
			email,
		});
		return response.data;
	},
	checkPassport: async () => {
		const response = await api.get(`/api/check-passport`, {
			headers: headersWithToken(),
		});
		return response.data;
	},
	getSocialURL: async (social) => {
		const response = await api.get(`api/auth/${social}/url`);
		return response.data;
	},
	getSocialCallback: async (social, search) => {
		const response = await api.get(`/api/auth/${social}/callback${search}`);
		return response.data;
	},
};

// Using fetch
const settingsPost = {
	method: "POST",
	headers: {
		Accept: "application/json",
		"Content-Type": "application/json",
		"Access-Control-Allow-Origin": "http://localhost:3000",
		"Access-Control-Allow-Credentials": "false",
	},
};
const settingsGet = {
	method: "GET",
	headers: {
		Accept: "application/json",
		"Content-Type": "application/json",
		"Access-Control-Allow-Origin": "http://localhost:3000",
		"Access-Control-Allow-Credentials": "false",
	},
};
export const authAPIFetch = {
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
	checkPassport: async () => {
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
