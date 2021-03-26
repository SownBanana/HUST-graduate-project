import { createSlice } from "@reduxjs/toolkit";

const initialState = {
	previousURL: "/",
	currentURL: "/",
};

const common = createSlice({
	name: "common",
	initialState,
	reducers: {
		setPreviousURL: (state, action) => {
			state.previousURL = action.payload;
		},
		setCurrentURL: (state, action) => {
			state.currentURL = action.payload;
		},
	},
});

export const { setPreviousURL, setCurrentURL } = common.actions;

export default common.reducer;
