import LockOutlinedIcon from "@material-ui/icons/LockOutlined";
import {
	Typography,
	Grid,
	makeStyles,
	CssBaseline,
	Paper,
	Avatar,
	TextField,
	Button,
} from "@material-ui/core/";

import { Link, Redirect } from "react-router-dom";
import { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { authFail, login as postLogin, resendVerify } from "../../authSlices";
import { enqueueSnackbar } from "../../../Toast/toastSlices";
import SnackButton from "../../../Toast/SnackButton";
import BackToPrevious from "../../../../commons/components/BackToPrevious";

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
	textLeft: {
		textAlign: "left",
	},
	textRight: {
		textAlign: "right",
	},
	noMargin: {
		margin: 0,
	},
	whiteButton: {
		color: "white",
	},
}));

function Login() {
	const isAuthed = useSelector((state) => state.auth.isLoggedIn);
	const wrongLogin = useSelector((state) => state.auth.error.wrongLogin);
	const wrongPass = useSelector((state) => state.auth.error.wrongPass);
	const notConfirm = useSelector((state) => state.auth.error.notConfirm);

	const [login, setLogin] = useState("");
	const [password, setPassword] = useState("");
	// const { register, handleSubmit, watch, errors } = useForm();

	const classes = useStyles();
	const dispatch = useDispatch();

	const handleChange = (e, func) => {
		func(e.target.value);
	};

	useEffect(() => {
		if (isAuthed) {
			const key = new Date().getTime() + Math.random();
			dispatch(
				enqueueSnackbar({
					key: key,
					message: "Bạn đã đăng nhập.",
					options: {
						key: key,
						// preventDuplicate: true,
						variant: "success",
						autoHideDuration: 1000,
						anchorOrigin: {
							vertical: "top",
							horizontal: "center",
						},
						action: (key) => <SnackButton notifyKey={key} />,
					},
				})
			);
		}
	}, [isAuthed, dispatch]);

	return isAuthed ? (
		// <Redirect to="/" />
		<BackToPrevious />
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
						Đăng nhập
					</Typography>
					<form
						className={classes.form}
						onSubmit={(e) => {
							e.preventDefault();
							dispatch(postLogin({ login, password }));
						}}
					>
						<TextField
							error={wrongLogin || notConfirm !== ""}
							variant="outlined"
							margin="normal"
							required
							fullWidth
							id="email"
							label="Email/Tên đăng nhập"
							name="email"
							autoComplete="email"
							autoFocus
							value={login}
							onChange={(e) => handleChange(e, setLogin)}
							helperText={
								notConfirm !== "" && (
									<p className={classes.noMargin}>
										Bạn chưa xác thực Email. Nhấn vào{" "}
										<a
											onClick={(e) => {
												e.preventDefault();
												dispatch(resendVerify(notConfirm));
												dispatch(authFail());
											}}
											href={`${process.env.REACT_APP_BACKEND_URL}/api/resend-confirm/${notConfirm}`}
										>
											đây
										</a>{" "}
										để gửi lại.
									</p>
								)
							}
						/>
						{/* <a href="#">here</a> */}
						<TextField
							error={wrongPass}
							variant="outlined"
							margin="normal"
							required
							fullWidth
							name="password"
							label="Mật khẩu"
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
							Đăng nhập
						</Button>
						<Grid container>
							<Grid item xs={6} className={classes.textLeft}>
								<Link to="#" variant="body2">
									Quên mật khẩu
								</Link>
							</Grid>
							<Grid item xs={6} className={classes.textRight}>
								<Link to={`register`}>Nhấn vào đây để đăng ký</Link>
							</Grid>
						</Grid>
					</form>
				</div>
			</Grid>
		</Grid>
	);
}

export default Login;
