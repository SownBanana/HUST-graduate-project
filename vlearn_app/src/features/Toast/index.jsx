import React from "react";
import { Slide, Snackbar } from "@material-ui/core";
import MuiAlert from "@material-ui/lab/Alert";
import { useDispatch, useSelector } from "react-redux";
import {
	removeSnackbar,
	// ToastType,
} from "./toastSlices";
// import { SnackbarProvider, useSnackbar } from "notistack";

function SlideTransition(props) {
	return <Slide {...props} direction="left" />;
}

export default function Toast() {
	const { open, duration, message, type } = useSelector((state) => state.toast);
	const dispatch = useDispatch();

	const handleCloseToast = (event, reason) => {
		if (reason === "clickaway") {
			return;
		}
		dispatch(removeSnackbar());
	};

	return (
		<div>
			<Snackbar
				open={open}
				autoHideDuration={duration}
				onClose={handleCloseToast}
				anchorOrigin={{ vertical: "top", horizontal: "right" }}
				TransitionComponent={SlideTransition}
			>
				<MuiAlert
					// elevation={1000}
					variant="filled"
					onClose={handleCloseToast}
					severity={type}
				>
					{message}
				</MuiAlert>
			</Snackbar>
		</div>
	);
}
