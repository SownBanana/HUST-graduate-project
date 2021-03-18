export const addAuthHeader = (options) => {
	const update = { ...options };
	if (localStorage) {
		const access_token = JSON.parse(localStorage.getItem("auth")).access_token;
		update.headers = {
			...update.headers,
			Authorization: `Bearer ${access_token}`,
		};
	}
	return update;
};
export const addBody = (options, body) => {
	let update = { ...options };
	update = {
		...update,
		body: JSON.stringify(body),
	};
	return update;
};
