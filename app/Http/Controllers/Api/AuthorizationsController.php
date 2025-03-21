<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthorizationRequest;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Http\Requests\Api\WeappAuthorizationRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Overtrue\LaravelSocialite\Socialite;

class AuthorizationsController extends Controller
{
    public function socialStore($type, SocialAuthorizationRequest $request)
    {
        $driver = Socialite::create($type);

        try {
            if ($code = $request->code) {
                $oauthUser = $driver->userFromCode($code);
            } else {
                $tokenData['access_token'] = $request->access_token;

                if ($type == 'wechat') {
                    $driver->withOpenid($request->openid);
                }

                $oauthUser = $driver->userFromToken($request->access_token);
            }
        } catch (\Exception $e) {
            throw new AuthenticationException('参数错误，未获取用户信息');
        }

        if (!$oauthUser->getId()) {
            throw new AuthenticationException('参数错误，未获取用户信息');
        }

        switch ($type) {
            case 'wechat':
                $unionid = $oauthUser->getRaw()['unionid'] ?? null;

                if ($unionid) {
                    $user = User::where('weixin_unionid', $unionid)->first();
                } else {
                    $user = User::where('weixin_openid', $oauthUser->getId())->first();
                }
                //  没有用户，默认创建一个用户
                if (!$user) {
                    $user = User::create([
                        'name' => $oauthUser->getNickname(),
                        'avatar' => $oauthUser->getAvatar(),
                        'weixin_openid' => $oauthUser->getId(),
                        'weixin_unionid' => $unionid,
                    ]);
                }
                break;
        }

        $token = auth('api')->fromUser($user);

        return $this->responseWithToken($token)->setStatusCode(201);
    }

    public function store(AuthorizationRequest $request)
    {
        $username = $request->username;

        filter_var($username, FILTER_VALIDATE_EMAIL) ? $credentials['email'] = $username : $credentials['phone'] = $username;

        $credentials['password'] = $request->password;

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            throw new AuthenticationException(trans('auth.failed'));
        }

        return $this->responseWithToken($token)->setStatusCode(201);
    }

    public function weappStore(WeappAuthorizationRequest $request)
    {
        $code = $request->code;
        //  根据 code 获取微信 openid 和 session_key
        $miniProgram = app('wechat.mini_program');
        $data = $miniProgram->auth->session($code);

        if (isset($data['errcode'])) {
            throw new AuthenticationException('code 不正确');
        }

        //  找不到 openid 对应的用户
        $user = User::where('weapp_openid', $data['openid'])->first();

        $attributes['weixin_session_key'] = $data['session_key'];

        if (!$user) {
            if (!$request->username) {
                throw new AuthenticationException('用户不存在');
            }

            $username = $request->username;

            filter_var($username, FILTER_VALIDATE_EMAIL) ?
                $credentials['email'] = $username :
                $credentials['phone'] = $username;

            $credentials['password'] = $request->password;

            if (!auth('api')->once($credentials)) {
                throw new AuthenticationException('用户名或密码错误');
            }

            $user = auth('api')->getUser();
            $attributes['weapp_openid'] = $data['openid'];
        }

        $user->update($attributes);

        $token = auth('api')->fromUser($user);

        return $this->responseWithToken($token)->setStatusCode(201);
    }

    public function responseWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function update()
    {
        $token = auth('api')->refresh();
        return $this->responseWithToken($token);
    }

    public function destroy()
    {
        auth('api')->logout();
        return response(null, 204);
    }
}
