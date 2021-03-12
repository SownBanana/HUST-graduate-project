import { combineReducers } from "redux";
import auth from "./features/Authenticate/authSlices";
export default combineReducers({
	auth,
});
