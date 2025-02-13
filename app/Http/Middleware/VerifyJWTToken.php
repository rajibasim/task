<?php
namespace App\Http\Middleware;
use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
class VerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try{
            //$user = JWTAuth::toUser($request->input('token'));
            $user = JWTAuth::toUser($request->header('token'));
        }catch (JWTException $e) {
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['result' => false, 'message' => 'Token expired'], $e->getStatusCode());
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['result' => false, 'message' => 'Token invalid'], $e->getStatusCode());
            }else{
                return response()->json(['result' => false, 'message'=>'Token is required']);
            }
        }
       return $next($request);
    }
}
?>
