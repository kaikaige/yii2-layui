<?php

namespace kaikaige\layui\controllers;

use kaikaige\layui\components\SearchModel;
use kaikaige\layui\models\SysConfigCate;
use Yii;
use kaikaige\layui\models\SysConfig;
use yii\data\ActiveDataProvider;
use kaikaige\layui\base\Controller;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SysConfigController implements the CRUD actions for SysConfig model.
 */
class SysConfigController extends Controller
{
    protected $modelClass = SysConfig::class;

    /**
     * Lists all GoodsType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new $this->modelClass;
        $cate_id = Yii::$app->request->get('cate_id');
        if ($cate_id == null) {
            throw new BadRequestHttpException('Param "cate_id" cannot be empty');
        }
        if (Yii::$app->request->isAjax) {
            $searchModel = $this->searchModel == null ? new SearchModel([
                'model' => $this->modelClass,
                'defaultOrder' => ['id' => SORT_DESC],
                'scenario' => 'default',
            ]) : $this->searchModel;
            $searchModel->query->andWhere(['id'=>87]);
            return $searchModel->ajaxSearch( Yii::$app->request->get($model->formName()));
        } else {
            return $this->render('index', [
                'searchModel' => $model,
                'model' => new SysConfigCate()
            ]);
        }

    }
}
