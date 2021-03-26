import React, { Component } from "react";
import styled from "styled-components";
import { ThemeContext } from "../index";

const H1Styled = styled.h1`
	color: ${(props) => (props.theme === "light" ? "#1f1f1f" : "#eee")};
	font-size: 3rem;
	text-transform: uppercase;
`;

const H2Styled = styled.h2`
	color: ${(props) => (props.theme === "light" ? "#1f1f1f" : "#eee")};
	font-size: 1.75rem;
	text-transform: uppercase;
`;

const H3Styled = styled.h3`
	color: ${(props) => (props.theme === "light" ? "#1f1f1f" : "#eee")};
	font-size: 1.5rem;
`;

const PStyled = styled.p`
	color: ${(props) => (props.theme === "light" ? "#1f1f1f" : "#eee")};
	font-size: 1rem;
`;

export class H1 extends Component {
	render() {
		return (
			<ThemeContext.Consumer>
				{(theme) => <H1Styled {...this.props} theme={theme} />}
			</ThemeContext.Consumer>
		);
	}
}
H1.contextType = ThemeContext;

export class H2 extends Component {
	render() {
		return (
			<ThemeContext.Consumer>
				{(theme) => <H2Styled {...this.props} theme={theme} />}
			</ThemeContext.Consumer>
		);
	}
}
H2.contextType = ThemeContext;

export class H3 extends Component {
	render() {
		return (
			<ThemeContext.Consumer>
				{(theme) => <H3Styled {...this.props} theme={theme} />}
			</ThemeContext.Consumer>
		);
	}
}
H3.contextType = ThemeContext;

export class P extends Component {
	render() {
		return (
			<ThemeContext.Consumer>
				{(theme) => <PStyled {...this.props} theme={theme} />}
			</ThemeContext.Consumer>
		);
	}
}
P.contextType = ThemeContext;
