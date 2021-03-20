import axios from "axios";

export const headersWithToken = (access_token = null) => {
	if (access_token === null && localStorage) {
		const access_token = JSON.parse(localStorage.getItem("auth")).access_token;
		return {
			Authorization: `Bearer ${access_token}`,
		};
	} else if (access_token !== null) {
		return {
			Authorization: `Bearer ${access_token}`,
		};
	}
	return {};
};

export default axios.create({
	baseURL: `${process.env.REACT_APP_BACKEND_URL}`,
	headers: {
		Accept: "application/json",
		"Content-Type": "application/json",
		"Access-Control-Allow-Origin": "http://localhost:3000",
		"Access-Control-Allow-Credentials": "true",
	},
});
