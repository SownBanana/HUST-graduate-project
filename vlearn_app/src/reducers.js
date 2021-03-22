import { combineReducers } from "redux";
import auth from "./features/Authenticate/authSlices";
import toast from "./features/Toast/toastSlices";
import common from "./commons/SliceCommon";
export default combineReducers({
	auth,
	toast,
	common,
});
