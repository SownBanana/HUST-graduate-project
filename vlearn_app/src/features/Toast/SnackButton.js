import { IconButton } from "@material-ui/core";
import CloseIcon from "@material-ui/icons/Close";
import React from "react";
import { useDispatch } from "react-redux";
import { closeSnackbar } from "./toastSlices";
import PropTypes from "prop-types"; // ES6

const whiteButton = {
	color: "white",
};

export default function SnackButton({
	notifyKey,
	action = closeSnackbar,
	children = <CloseIcon />,
}) {
	const dispatch = useDispatch();
	return (
		<IconButton
			style={whiteButton}
			onClick={(e) => {
				e.preventDefault();
				dispatch(action(notifyKey));
			}}
		>
			{children}
		</IconButton>
	);
}

SnackButton.propTypes = {
	notifyKey: PropTypes.number.isRequired,
	action: PropTypes.func,
};
