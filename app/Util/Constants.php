<?php
/**
 * Created by PhpStorm.
 * User: Parthib
 * Date: 12/11/2018
 * Time: 12:33 PM
 */

namespace App;


class Constants
{
    const CURRENT_USER_ROLE_KEY = "___current_user_role";
    const CURRENT_USER_ROLE_ID_KEY = "___current_user_role_id";
    const CURRENT_USERNAME_KEY = "___current_username";
    const CURRENT_USER_ID_KEY = "___current_user_id";
}


class UserRoleType
{
    const ADMIN = "admin";
    const USER = "user";
}


class UserRoleIds
{
    const ADMIN = 1;
    const USER = 2;
}
