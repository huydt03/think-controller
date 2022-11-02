<?php
declare (strict_types = 1);

namespace Huydt\ThinkController;
use JWT;
use app\model\User;

class Auth
{

    public static $user_id;

    public static function user(){
        return User::find(self::$user_id);
    }

    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $jwt_token = $request-> header('auth');

        if(!$jwt_token)
            return json(['status'=> 0, 'data'=> 'AccessToken denied!']);

        try{
            $payload = JWT::decode($jwt_token);
            self::$user_id = $payload-> sub;
            return $next($request);
        }catch(\Exception $e){
            return response($e-> getMessage())-> code(401);
        }
    }
}
