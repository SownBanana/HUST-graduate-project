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
} from "@material-ui/core/";
import { Link } from "react-router-dom";
import { useDispatch } from "react-redux";
import { register } from "../../authSlices";
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
	const classes = useStyles();
	const [name, setName] = useState("");
	const [username, setUsername] = useState("");
	const [email, setEmail] = useState("");
	const [password, setPassword] = useState("");
	const [repassword, setRepassword] = useState("");
	const handleChange = (e, func) => {
		func(e.target.value);
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
						onSubmit={(e) => {
							e.preventDefault();
							dispatch(register({ name, username, email, password }));
						}}
					>
						<Grid container spacing={2}>
							<Grid item xs={12}>
								<TextField
									autoComplete="name"
									name="name"
									variant="outlined"
									required
									fullWidth
									id="name"
									label="Họ tên"
									autoFocus
									onChange={(e) => handleChange(e, setName)}
									value={name}
								/>
							</Grid>
							<Grid item xs={12}>
								<TextField
									variant="outlined"
									required
									fullWidth
									id="username"
									label="Tên tài khoản"
									name="username"
									autoComplete="username"
									onChange={(e) => handleChange(e, setUsername)}
									value={username}
								/>
							</Grid>
							<Grid item xs={12}>
								<TextField
									variant="outlined"
									required
									fullWidth
									id="email"
									label="Địa chỉ Email"
									name="email"
									autoComplete="email"
									onChange={(e) => handleChange(e, setEmail)}
									value={email}
								/>
							</Grid>
							<Grid item xs={12}>
								<TextField
									variant="outlined"
									required
									fullWidth
									name="password"
									label="Mật khẩu"
									type="password"
									id="password"
									autoComplete="current-password"
									onChange={(e) => handleChange(e, setPassword)}
									value={password}
								/>
							</Grid>
							<Grid item xs={12}>
								<TextField
									variant="outlined"
									required
									fullWidth
									name="repassword"
									label="Nhập lại mật khẩu"
									type="repassword"
									id="repassword"
									autoComplete="password"
									onChange={(e) => handleChange(e, setRepassword)}
									value={repassword}
								/>
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
