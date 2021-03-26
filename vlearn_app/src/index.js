import React from "react";
import ReactDOM from "react-dom";
import "./index.css";
import App from "./App";
import reportWebVitals from "./reportWebVitals";
import { BrowserRouter as Router } from "react-router-dom";
import { Provider } from "react-redux";
import { configureStore, getDefaultMiddleware } from "@reduxjs/toolkit";
import rootReducer from "./reducers";
import RefreshTokenMiddleWare from "./middlewares/RefreshTokenMiddleWare";
import logger from "redux-logger";
import { SnackbarProvider } from "notistack";

const store = configureStore({
	reducer: rootReducer,
	middleware: [
		RefreshTokenMiddleWare,
		...getDefaultMiddleware({
			serializableCheck: {
				// Ignore these action types
				ignoredActions: ["toast"],
				// Ignore these field paths in all actions
				ignoredActionPaths: ["payload.options.action"],
				// Ignore these paths in the state
				ignoredPaths: ["toast.notifications"],
			},
		}),
		logger,
	],
});

ReactDOM.render(
	<React.StrictMode>
		<Provider store={store}>
			<SnackbarProvider
				dense
				anchorOrigin={{
					vertical: "top",
					horizontal: "right",
				}}
			>
				<Router>
					<App />
				</Router>
			</SnackbarProvider>
		</Provider>
	</React.StrictMode>,
	document.getElementById("root")
);

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();
// export default store;
