<?php
/**
 * Created by PhpStorm.
 * User: coder
 * Date: 2016/7/19
 * Time: 14:05
 */

namespace App\Http\Controllers\Auth;


trait AuthAndRegisterTrait
{
    use AuthCustomTrait, RegisterCustomTrait {
        AuthCustomTrait::redirectPath insteadof RegisterCustomTrait;
        AuthCustomTrait::getGuard insteadof RegisterCustomTrait;
    }
}