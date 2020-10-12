<?php

namespace kaikaige\layui\controllers;

use kaikaige\layui\components\SearchModel;
use kaikaige\layui\models\SysLog;
use Yii;
use yii\data\ActiveDataProvider;
use kaikaige\layui\base\Controller;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SysLogController implements the CRUD actions for SysLog model.
 */
class SysLogController extends Controller
{
    protected $modelClass = SysLog::class;

    /**
     * Lists all GoodsType models.
     * @return mixed
     */
    public function actionIndex()
    {
        /* @var $model ActiveRecord*/
        $model = new $this->modelClass;
        if (Yii::$app->request->isAjax) {
            $searchModel = new SearchModel([
                'model' => $this->modelClass,
                'defaultOrder' => ['id' => SORT_DESC],
                'scenario' => 'default',
                'partialMatchAttributes' => ['message', 'category', 'context', 'ip'],
            ]);
            return $searchModel->ajaxSearch( Yii::$app->request->get($model->formName()), function($data) {
                foreach ($data as $key=>$log) {
                    foreach ($log as $v=>$val) {
                        $data[$key][$v] = Html::encode($val);
                    }
                }
                return $data;
            });
        } else {
            return $this->render('index', [
                'searchModel' => $model,
            ]);
        }
    }

    /**
     * @des 清除系统日志
     * @param $sys
     * @date 2020/1/15 10:38 上午
     * @author gaokai
     * @modified_date 2020/1/15 10:38 上午
     * @modified_user gaokai
     * @throws \yii\db\Exception
     */
    public function actionClear($sys) {
        $cmd = Yii::$app->db->createCommand();
        $cmd->truncateTable(SysLog::$sys[$sys]);
        $cmd->execute();
    }
}
