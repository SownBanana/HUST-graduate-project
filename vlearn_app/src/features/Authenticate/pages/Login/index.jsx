import LockOutlinedIcon from "@material-ui/icons/LockOutlined";
import {
	Typography,
	// Link,
	Grid,
	makeStyles,
	CssBaseline,
	Paper,
	Avatar,
	TextField,
	// FormControlLabel,
	// Checkbox,
	Button,
} from "@material-ui/core/";

import { Link, Redirect } from "react-router-dom";
import { useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import {
	authSuccess,
	authFail,
	refreshSuccess,
	refreshFail,
	login as postLogin,
} from "../../authSlices";

const useStyles = makeStyles((theme) => ({
	root: {
		height: "100vh",
	},
	image: {
		backgroundImage: "url(https://source.unsplash.com/random)",
		backgroundRepeat: "no-repeat",
		backgroundColor:
			theme.palette.type === "light"
				? theme.palette.grey[50]
				: theme.palette.grey[900],
		backgroundSize: "cover",
		backgroundPosition: "center",
	},
	paper: {
		margin: theme.spacing(8, 4),
		display: "flex",
		flexDirection: "column",
		alignItems: "center",
	},
	avatar: {
		margin: theme.spacing(1),
		backgroundColor: theme.palette.secondary.main,
	},
	form: {
		width: "100%", // Fix IE 11 issue.
		marginTop: theme.spacing(1),
	},
	submit: {
		margin: theme.spacing(3, 0, 2),
	},
}));

function Login() {
	const isAuthed = useSelector((state) => state.auth.isLoggedIn);
	const [login, setLogin] = useState("");
	const [password, setPassword] = useState("");
	const classes = useStyles();
	const dispatch = useDispatch();
	const handleChange = (e, func) => {
		func(e.target.value);
	};
	return isAuthed ? (
		<Redirect to="/" />
	) : (
		<Grid container component="main" className={classes.root}>
			<CssBaseline />
			<Grid item xs={false} sm={4} md={7} className={classes.image} />
			<Grid item xs={12} sm={8} md={5} component={Paper} elevation={6} square>
				<div className={classes.paper}>
					<Avatar className={classes.avatar}>
						<LockOutlinedIcon />
					</Avatar>
					<Typography component="h1" variant="h5">
						Sign in
					</Typography>
					<form
						className={classes.form}
						onSubmit={(e) => {
							e.preventDefault();
							console.log(login, password);
							dispatch(postLogin(login, password));
						}}
					>
						<TextField
							variant="outlined"
							margin="normal"
							required
							fullWidth
							id="email"
							label="Email Address"
							name="email"
							autoComplete="email"
							autoFocus
							value={login}
							onChange={(e) => handleChange(e, setLogin)}
						/>
						<TextField
							variant="outlined"
							margin="normal"
							required
							fullWidth
							name="password"
							label="Password"
							type="password"
							id="password"
							autoComplete="current-password"
							value={password}
							onChange={(e) => handleChange(e, setPassword)}
						/>
						{/* <FormControlLabel
							control={<Checkbox value="remember" color="primary" />}
							label="Remember me"
						/> */}
						<Button
							type="submit"
							fullWidth
							variant="contained"
							color="primary"
							className={classes.submit}
						>
							Sign In
						</Button>
						<Grid container>
							<Grid item xs>
								<Link to="#" variant="body2">
									Forgot password?
								</Link>
							</Grid>
							<Grid item>
								<Link to={`register`} variant="body2">
									Don't have an account? Sign Up
								</Link>
							</Grid>
						</Grid>
					</form>
				</div>
			</Grid>
		</Grid>
	);
}

export default Login;
