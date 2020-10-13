<?php
namespace kaikaige\layui\controllers;

use kaikaige\layui\forms\LoginForm;
use kaikaige\layui\forms\UpPasswordForm;
use yii;
use kaikaige\layui\models\AuthMenu;
use mdm\admin\components\Helper;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: gaokai
 * Date: 2019-11-19
 * Time: 15:03
 */

class HomeController extends \kaikaige\layui\base\Controller
{
    public $layout = "@kaikaige/layui/views/layouts/main";

    public function actionIndex() {
        $menus = AuthMenu::find()->orderBy(['order'=>SORT_ASC])->asArray()->all();
        $this->view->title = $this->module->title;
        $menus = $this->treeMenus($menus, 0);
//        f_d($menus);
        return $this->render('index', [
            'menus' => $menus,
            'defaultUrl' => Url::to($this->module->homeUrl),
        ]);
    }

    public function actionLogin()
    {
        $this->layout = false;
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if (Yii::$app->request->isAjax) {
            if (!$model->load(Yii::$app->request->post()) || !$model->login()) {
                return $this->asJson(['code'=>400, 'message'=>$this->modelError($model)]);
            } else {
                return $this->asJson(['code'=>200, 'message'=>'登录成功']);
            }
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionWelcome() {
        $this->layout = false;
        return $this->render('welcome');
    }

    private function treeMenus($data, $pId) {
        $tree = [];
        foreach($data as $k => $v)
        {
            if($v['parent'] == $pId)
            {        //父亲找到儿子
                if (Helper::checkRoute($v['route'], \Yii::$app->getRequest()->get())) {
                    $v['items'] = $this->treeMenus($data, $v['id']);
                    $tree[] = $v;
                }
                //unset($data[$k]);
            }
        }
        return $tree;
    }

    public function actionTheme() {
        return $this->render('theme');
    }

    public function actionUpPswd()
    {
//        return Yii::$app->security->generatePasswordHash("admin");
        $model = new UpPasswordForm();
        if (Yii::$app->request->isAjax) {
            $model->load(Yii::$app->request->post(), '');
            $model->update();
            return $model;
        } else {
            return $this->render('up-pswd');
        }

    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    private function modelError($model) {
        $errors = $model->getErrors();		//得到所有的错误信息
        if(!is_array($errors)){
            return true;
        }
        $firstError = array_shift($errors);
        if(!is_array($firstError)) {
            return true;
        }
        return array_shift($firstError);
    }
}