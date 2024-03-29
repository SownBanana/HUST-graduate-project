<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Events\PrivateMessageSend;
use App\Http\Controllers\Controller;
use App\Http\Proxy\AuthenticateProxy;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Jobs\SendResetPasswordEmail;
use App\Jobs\SendVerifyEmail;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    private $authProxy;

    public function __construct(AuthenticateProxy $authProxy)
    {
        $this->authProxy = $authProxy;
    }

    public function register(RegisterRequest $request)
    {
        # Can't make a admin/mod account
        if ($request->role != null && ($request->role == UserRole::Admin || $request->role == UserRole::Mod)) {
            return response()->json(['error'=>'You don\'t have permission to create admin/mod account'], 403);
        }
        $confirmation_code = time().uniqid(true);
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['confirmation_code'] = $confirmation_code;
        $user = User::create($input);
        if ($request->role == UserRole::Instructor) {
            $title = new Instructor;
        } else {
            $title = new Student;
        }
        # Send verify email by mails redis queue
        dispatch(new SendVerifyEmail($user))->onQueue('mails');
        $title->user()->associate($user);
        $title->receive_email = $user->email;
        $title->save();
        $data['confirmation_code'] = $user->confirmation_code;
        $success['username'] =  $user->username;
        return response()->json(['success'=>$success], 201);
    }

    public function login(LoginRequest $request)
    {
        $login_info = $request->login;
        // var_dump(User::where('email', $login_info)->first() != null);
        $isEmail = filter_var($login_info, FILTER_VALIDATE_EMAIL);
        if ($isEmail) {
            # user sent their email
            $credentials['email'] = $login_info;
        } else {
            # they sent their username instead
            $credentials['username'] = $login_info;
        }
        $credentials['password'] = $request->password;
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            # Check if user verify email
            if ($user->email_verified_at == null) {
                return response()->json(['status'=>'notConfirm','email'=>$user->email]);
            }
            $response = $this->authProxy->attemptLogin($login_info, $request->password);
            $response['user'] = $user->toArray();
            // dump($response);
            return response($response);
        } else {
            if ($isEmail) {
                if (User::where('email', $login_info)->first() == null) {
                    $mess = 'Email chưa được sử dụng';
                    $type = 'login';
                } else {
                    $mess = 'Mật khẩu không chính xác';
                    $type = 'password';
                }
            } else {
                if (User::where('username', $login_info)->first() == null) {
                    $mess = 'Tên đăng nhập không tồn tại';
                    $type = 'login';
                } else {
                    $mess = 'Mật khẩu không chính xác';
                    $type = 'password';
                }
            }
            return response()->json(['status'=>'failed', 'type'=>$type, 'message'=>$mess]);
        }
    }

    public function requestChangePassword(Request $request)
    {
        $login_info = $request->email;
        if (filter_var($login_info, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $login_info)->first();
        } else {
            $user = User::where('username', $login_info)->first();
        }
        if ($user) {
            $confirmation_code = time().uniqid(true);
            $user->confirmation_code = $confirmation_code;
            $user->save();
            dispatch(new SendResetPasswordEmail($user))->onQueue('mails');
            return response()->json(['status'=>'success','mss'=>'Đã gửi email xác thực cho '.$user->email]);
        }
        return response()->json(['status'=>'error', 'mss'=>'Không tìm thấy thông tin đăng nhập '.$login_info]);
    }
    public function verifyReset(Request $request)
    {
        $user = User::where('confirmation_code', $request->code)->first();
        if ($user) {
            $user->confirmation_code = null;
            $user->touchVerifyEmail();
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json(['status'=>'success']);
        } else {
            abort(404, 'Link xác thực hết hạn');
        }
        return 404;
    }
    public function resendConfirm(Request $request)
    {
        $login_info = $request->email;
        if (filter_var($login_info, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $login_info)->first();
        } else {
            $user = User::where('username', $login_info)->first();
        }
        if ($user) {
            $confirmation_code = time().uniqid(true);
            $user->confirmation_code = $confirmation_code;
            $user->save();
            dispatch(new SendVerifyEmail($user))->onQueue('mails');
            return response()->json(['status'=>'success','mss'=>'Đã gửi email xác thực cho '.$user->email]);
        }
        return response()->json(['status'=>'error', 'mss'=>'Không tìm thấy thông tin đăng nhập '.$login_info]);
    }
    public function verify($code)
    {
        $user = User::where('confirmation_code', $code)->first();
        if ($user) {
            $user->confirmation_code = null;
            $user->touchVerifyEmail();
            return Redirect::to(Config::get('app.react_url', 'localhost'));
        } else {
            abort(404, 'Link xác thực hết hạn');
        }
        return 404;
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->refresh_token;
        return response($this->authProxy->attemptRefresh($refreshToken));
    }

    public function logout(Request $request)
    {
        $this->authProxy->logout();
        return response(null, 204);
    }

    /**
 * @OA\Get(
 *      path="/api/check-passport",
 *      tags={"Check passport (Check Auth)"},
 *      @OA\Response(
 *         response=200,
 *         description="Return your user model.",
 *     ),
 *      @OA\Response(response="401", description="You aren't login yet")
 * )
 */
    public function check_passport()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], 200);
    }

    public function getUserFromLoginInfor($login_info)
    {
        if (filter_var($login_info, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $login_info)->first();
        } else {
            $user = User::where('username', $login_info)->first();
        }
        return $user;
    }

    public function checkLoginAvailable(Request $request)
    {
        $login_info = $request->login;
        if ($user = $this->getUserFromLoginInfor($login_info)) {
            return response()->json(['status'=>"existed"]);
        }
        return response()->json(['status'=>"available"]);
    }
}
