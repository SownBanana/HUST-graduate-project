import React from "react";
import { useSelector } from "react-redux";
import { Redirect } from "react-router";

export default function BackToPrevious() {
	const previousURL = useSelector((state) => state.common.previousURL);
	return <Redirect to={previousURL ? previousURL : "/"} />;
}
