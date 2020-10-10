<?php
namespace kaikaige\layui\models\menus;

class StatusMenu
{
    const STATYS_OFF = 0;

    const STATYS_ON = 1;

    public static $statusList = [
        self::STATYS_OFF => '禁用',
        self::STATYS_ON => '正常',
    ];
}
