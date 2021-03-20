import { createSlice } from "@reduxjs/toolkit";

export const ToastType = {
	SUCCESS: "success",
	ERROR: "error",
	WARNING: "warning",
	INFO: "info",
};

// const initialState = {
// 	open: false,
// 	type: ToastType.SUCCESS,
// 	title: "",
// 	message: "",
// 	duration: 3000,
// };

const initialState = {
	notifications: [],
};

// notification:{
// 	key: timestamp,
// 	message: "",
// 	option: {},
// 	dismissed = false
// }

const toast = createSlice({
	name: "toast",
	initialState,
	reducers: {
		// openToast: (state, action) => {
		// 	state.type = action.payload.type;
		// 	state.title = action.payload.title;
		// 	state.message = action.payload.message;
		// 	state.duration = action.payload.duration
		// 		? action.payload.duration
		// 		: state.duration;
		// 	state.open = true;
		// },

		// closeToast: (state) => {
		// 	console.log("close");
		// 	state.open = false;
		// 	state.duration = 3000;
		// },
		enqueueSnackbar: (state, action) => {
			state.notifications.push(action.payload);
		},
		closeSnackbar: (state, action) => {
			state.notifications = state.notifications.map((notification) =>
				notification.key === action.payload
					? { ...notification, dismissed: true }
					: { ...notification }
			);
			// 	notification.options.key === action.payload
			// 		? (notification.dismissed = true)
			// 		: notification
			// );
		},
		removeSnackbar: (state, action) => {
			state.notifications = state.notifications.filter(
				(notification) => notification.options.key !== action.payload
			);
		},
	},
});

export const {
	// openToast,
	//  closeToast,
	enqueueSnackbar,
	removeSnackbar,
	closeSnackbar,
} = toast.actions;

export default toast.reducer;
