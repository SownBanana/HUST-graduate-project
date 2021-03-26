import LockOutlinedIcon from "@material-ui/icons/LockOutlined";
import {
	Typography,
	Grid,
	makeStyles,
	CssBaseline,
	Avatar,
	TextField,
	Button,
	Container,
	Collapse,
} from "@material-ui/core/";
import { Link } from "react-router-dom";
import { useDispatch } from "react-redux";
import { register as reg } from "../../authSlices";
import { useForm } from "react-hook-form";
import globalStyles from "../../../../style/GlobalStyles";
import { useState } from "react";
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

export default function Register() {
	const dispatch = useDispatch();
	globalStyles();
	const classes = useStyles();
	const { register, handleSubmit, errors, getValues } = useForm();
	const onSubmit = ({ name, username, email, password }) => {
		dispatch(reg({ name, username, email, password }));
	};
	const [passwordErrorCheck, setPasswordErrorCheck] = useState({
		spe: false,
		cap: false,
		nor: false,
		num: false,
		timeOut: 0,
	});
	var timer = 0;
	const checkErrorPassword = (password) => {
		// console.log(timer);
		clearTimeout(timer);
		timer = setTimeout(() => {
			let check = false;
			//validate letter
			if (password.match(/[a-z]/)) {
				setPasswordErrorCheck((passwordErrorCheck) => {
					return { ...passwordErrorCheck, nor: false };
				});
			} else {
				check = true;
				setPasswordErrorCheck((passwordErrorCheck) => {
					return { ...passwordErrorCheck, nor: true };
				});
			}

			//validate capital letter
			if (password.match(/[A-Z]/)) {
				setPasswordErrorCheck((passwordErrorCheck) => {
					return { ...passwordErrorCheck, cap: false };
				});
			} else {
				check = true;
				setPasswordErrorCheck((passwordErrorCheck) => {
					return { ...passwordErrorCheck, cap: true };
				});
			}

			//validate special character
			if (password.match(/[!"#$%&'()*+,-.:;<=>?@[\]^_`{|}~]/)) {
				setPasswordErrorCheck((passwordErrorCheck) => {
					return { ...passwordErrorCheck, spe: false };
				});
			} else {
				check = true;
				setPasswordErrorCheck((passwordErrorCheck) => {
					return { ...passwordErrorCheck, spe: true };
				});
			}

			//validate number
			if (password.match(/\d/)) {
				setPasswordErrorCheck((passwordErrorCheck) => {
					return { ...passwordErrorCheck, num: false };
				});
			} else {
				check = true;
				setPasswordErrorCheck((passwordErrorCheck) => {
					return { ...passwordErrorCheck, num: true };
				});
			}
			// console.log(passwordErrorCheck);
			return !check;
		}, 100);
	};

	return (
		<Container component="main" maxWidth="sm">
			<CssBaseline />
			<Grid container>
				<div className={classes.paper}>
					<Avatar className={classes.avatar}>
						<LockOutlinedIcon />
					</Avatar>
					<Typography component="h1" variant="h5">
						Đăng ký tài khoản
					</Typography>
					<form
						className={classes.form}
						noValidate
						onSubmit={handleSubmit(onSubmit)}
					>
						<Grid container spacing={2}>
							<Grid item xs={12}>
								<TextField
									autoComplete="name"
									name="name"
									variant="outlined"
									fullWidth
									id="name"
									label="Họ tên"
									autoFocus
									inputRef={register({ required: true })}
									error={"name" in errors}
								/>
								<Collapse in={"name" in errors}>
									<div className="errorHelperText">Nhập tên của bạn</div>
								</Collapse>
							</Grid>
							<Grid item xs={12}>
								<TextField
									variant="outlined"
									fullWidth
									id="username"
									label="Tên tài khoản"
									name="username"
									autoComplete="username"
									inputRef={register({ required: true })}
									error={"username" in errors}
								/>
								<Collapse in={"username" in errors}>
									<div className="errorHelperText">
										Nhập tên tài khoản của bạn
									</div>
								</Collapse>
							</Grid>
							<Grid item xs={12}>
								<TextField
									variant="outlined"
									fullWidth
									id="email"
									label="Địa chỉ Email"
									name="email"
									autoComplete="email"
									inputRef={register({
										required: true,
										pattern: {
											value: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i,
										},
									})}
									error={"email" in errors}
								/>
								<Collapse
									in={"email" in errors && errors.email.type === "required"}
								>
									<div className="errorHelperText">
										Nhập địa chỉ email của bạn
									</div>
								</Collapse>
								<Collapse
									in={"email" in errors && errors.email.type === "pattern"}
								>
									<div className="errorHelperText">
										Địa chỉ email không hợp lệ
									</div>
								</Collapse>
							</Grid>
							<Grid item xs={12}>
								<TextField
									variant="outlined"
									fullWidth
									name="password"
									label="Mật khẩu"
									type="password"
									id="password"
									autoComplete="current-password"
									inputRef={register({
										required: true,
										minLength: 6,
										validate: checkErrorPassword,
									})}
									error={"password" in errors}
								/>
								<Collapse
									in={
										"password" in errors && errors.password.type === "required"
									}
								>
									<div className="errorHelperText">
										Nhập địa mật khẩu của bạn
									</div>
								</Collapse>
								<Collapse
									in={
										"password" in errors && errors.password.type === "minLength"
									}
								>
									<div className="errorHelperText">
										Mật khẩu của bạn quá ngắn
									</div>
								</Collapse>
								<Collapse in={passwordErrorCheck.spe}>
									<div className="errorHelperText">
										Mật khẩu của bạn cần chứa ít nhất 1 ký tự đặc biệt
									</div>
								</Collapse>
								<Collapse in={passwordErrorCheck.cap}>
									<div className="errorHelperText">
										Mật khẩu của bạn cần chứa ít nhất 1 ký tự in hoa
									</div>
								</Collapse>
								<Collapse in={passwordErrorCheck.nor}>
									<div className="errorHelperText">
										Mật khẩu của bạn cần chứa ít nhất 1 ký tự thường
									</div>
								</Collapse>
								<Collapse in={passwordErrorCheck.num}>
									<div className="errorHelperText">
										Mật khẩu của bạn cần chứa ít nhất 1 số
									</div>
								</Collapse>
							</Grid>
							<Grid item xs={12}>
								<TextField
									variant="outlined"
									fullWidth
									name="repassword"
									label="Nhập lại mật khẩu"
									type="password"
									id="repassword"
									autoComplete="password"
									inputRef={register({
										validate: (value) => value === getValues().password,
									})}
									error={"repassword" in errors}
								/>
								<Collapse in={"repassword" in errors}>
									<div className="errorHelperText">Mật khẩu không khớp</div>
								</Collapse>
							</Grid>
						</Grid>
						<Button
							type="submit"
							fullWidth
							variant="contained"
							color="primary"
							className={classes.submit}
						>
							Đăng ký
						</Button>
						<Grid container justify="flex-end">
							<Grid item>
								<Link to="login" variant="body2">
									Đã có tài khoản? Click vào đây để đăng nhập.
								</Link>
							</Grid>
						</Grid>
					</form>
				</div>
			</Grid>
		</Container>
	);
}
