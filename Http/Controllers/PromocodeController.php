<?php
/**
 * Created by PhpStorm.
 * User: yixin
 * Date: 2018/9/18
 * Time: 15:39
 */

namespace Modules\Sale\Http\Controllers;


use Gabievi\Promocodes\Facades\Promocodes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Http\Controllers\BasePublicController;
use Modules\User\Entities\Sentinel\User;

class PromocodeController extends BasePublicController
{
    /*获取用户优惠券列表*/
    public function promo()
    {
        if(Auth::check()){
            $user = user();
            $promocodes = $user->promocodes()->get();
        }
        return view('usercenter.promocode',compact('promocodes'));
    }
    /*领券并使用优惠券*/
    public function getCouponCode($code)
    {
        if( Auth::check() ){
            $user = user();
            $redeemMessage = $user->redeemCode($code, function ($promocode) use ($user) {
                return AjaxResponse::success( '恭喜' . $user->name . '获取价值' . $promocode->reward . '元优惠券, 有效期'. $promocode->expires_at );
            });
        }else{
            return AjaxResponse::faile('尚未登陆');
        }
    }
    /*检查优惠券是否可用*/
    public function checkCuponCode($code)
    {
        $res = Promocodes::check($code);
        return AjaxResponse::success('',['is_valid'=>$res]);
    }

    public function disableCuponCode($code)
    {
        $res = Promocodes::disable($code);
        return AjaxResponse::success('',['is_disabled'=>$res]);
    }
}