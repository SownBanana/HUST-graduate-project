import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import api from "../../../../commons/api/AuthAPI";
import BackToPrevious from "../../../../commons/components/BackToPrevious";
import { authSuccess } from "../../authSlices";
export default function Google({ location }) {
	const [response, setResponse] = useState({
		status: "init",
	});
	const isAuthed = useSelector((state) => state.auth.isLoggedIn);
	const dispatch = useDispatch();

	useEffect(() => {
		const promise = api.getSocialCallback("google", location.search);
		promise.then((data) => {
			setResponse(data);
			if (data.status === "success") {
				dispatch(authSuccess(data));
			}
		});
		return () => {};
	}, []);
	return (
		<div>
			{/* <div> {location.search}</div> */}
			{isAuthed && <BackToPrevious />}
			{response.status === "existed" && <div>Tai khoan da ton tai</div>}
			{response.status === "new" && <div>Tai khoan moi</div>}
		</div>
	);
}
