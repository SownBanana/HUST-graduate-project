import { Button } from "@material-ui/core";
import React from "react";
import { useDispatch } from "react-redux";
import { Link } from "react-router-dom";
import SnackButton from "../Toast/SnackButton";
import { checkPassport } from "../Authenticate/authSlices";
import { enqueueSnackbar } from "../Toast/toastSlices";

function Home() {
	const dispatch = useDispatch();
	return (
		<div>
			Home
			<Link to="/auth">auth</Link>
			<Button
				onClick={(e) => {
					e.preventDefault();
					// console.log("passport");
					dispatch(checkPassport());
				}}
			>
				Check Auth
			</Button>
			<Button
				variant="contained"
				onClick={(e) => {
					e.preventDefault();
					const key = new Date().getTime() + Math.random();
					dispatch(
						enqueueSnackbar({
							key: key,
							message: "Failed fetching data.",
							options: {
								key: key,
								// preventDuplicate: true,
								variant: "success",
								autoHideDuration: 2000,
								anchorOrigin: {
									vertical: "top",
									horizontal: "right",
								},
								action: (key) => <SnackButton notifyKey={key} />,
								// action: (
								// 	<Button onClick={() => alert("message") }>
								// 		'Alert'
								// 	</Button>
								// )
							},
						})
					);
				}}
			>
				Display snackbar
			</Button>
			{/* <Button variant="contained" onClick={closeSnackbar()}>
				Dismiss all snackbars
			</Button> */}
		</div>
	);
}

export default Home;
