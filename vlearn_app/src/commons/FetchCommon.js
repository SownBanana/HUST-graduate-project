export default function updateOptions(options) {
	const update = { ...options };
	if (localStorage) {
		const access_token = JSON.parse(localStorage.getItem("auth")).access_token;
		update.headers = {
			...update.headers,
			Authorization: `Bearer ${access_token}`,
		};
	}
	return update;
}
