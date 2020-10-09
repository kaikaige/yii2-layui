<?php
namespace kaikaige\layui\controllers;
use kaikaige\layui\models\AuthMenu;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Created by PhpStorm.
 * User: gaokai
 * Date: 2019-11-19
 * Time: 15:03
 */

class HomeController extends Controller
{
    public $layout = "@kaikaige/layui/views/layouts/main";

    public function actionIndex() {
        $menus = AuthMenu::find()->asArray()->all();
        return $this->render('index', [
            'menus' => $this->treeMenus($menus, 0),
            'defaultUrl' => Url::to($this->module->homeUrl),
        ]);
    }

    private function treeMenus($data, $pId) {
        $tree = [];
        foreach($data as $k => $v)
        {
            if($v['parent'] == $pId)
            {        //父亲找到儿子
                $v['items'] = $this->treeMenus($data, $v['id']);
                $tree[] = $v;
                //unset($data[$k]);
            }
        }
        return $tree;
    }

    public function actionTheme() {
        return $this->render('theme');
    }
}