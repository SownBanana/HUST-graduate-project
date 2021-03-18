import { combineReducers } from "redux";
import auth from "./features/Authenticate/authSlices";
import toast from "./features/Toast/toastSlices";
export default combineReducers({
	auth,
	toast,
});
