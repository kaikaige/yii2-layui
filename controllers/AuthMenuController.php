<?php

namespace kaikaige\layui\controllers;

use kaikaige\layui\components\SearchModel;
use Yii;
use kaikaige\layui\models\AuthMenu;
use yii\data\ActiveDataProvider;
use kaikaige\layui\base\Controller;
use yii\db\ActiveRecord;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthMenuController implements the CRUD actions for AuthMenu model.
 */
class AuthMenuController extends Controller
{
    protected $modelClass = AuthMenu::class;

    public function actionIndex($parent = 0)
    {
        $model = new $this->modelClass;
        if (Yii::$app->request->isAjax) {
            $ids = AuthMenu::find()->where(['parent'=>$parent])->select('id')->column();
            $data = AuthMenu::find()->where(['parent'=>$ids])->orWhere(['id'=>$ids])->all();
            return [
                'code' => 0,
                'message' => '',
                'count'=> count($data),
                'data' => $data,
            ];
        } else {
            $systems = AuthMenu::findAll(['parent'=>0]);
            return $this->render('index', [
                'searchModel' => $model,
                'systems' => $systems
            ]);
        }
    }

    public function actionCreate()
    {
        if (Yii::$app->request->isPost) {
            /* @var $model ActiveRecord */
            $model = new $this->modelClass();
            $model->load(Yii::$app->request->post());
            $model->save();
            return $model;
        } else {
            $parents = AuthMenu::findAll(['parent'=>0]);
            return $this->render('create', [
                'model' => new $this->modelClass
            ]);
        }
    }

    public function actionSystem()
    {
        $model = new $this->modelClass;
        if (Yii::$app->request->isAjax) {
            $data = AuthMenu::find()->where(['parent'=>0])->all();
            return [
                'code' => 0,
                'message' => '',
                'count'=> count($data),
                'data' => $data,
            ];
        } else {
            return $this->render('system');
        }
    }

    public function actionList($parent=0) {
        $models = AuthMenu::findAll(['parent'=>$parent]);
        $data = [];
        foreach ($models as $model) {
            $data[] = [
                'value' => $model->id,
                'label' => $model->name,
                'haveChildren' => $parent == 0 || $model->parent ==0,
            ];
        }
        return $data;
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $parent = $model->parent != 0 ? $this->findModel($model->parent) : null;

        $t = Yii::$app->db->beginTransaction();
        try {
            if($parent !== null && $parent->parent != 0) { //叶子节点
                $model->delete();
            } else if($parent !== null && $parent->parent == 0 ) { //二级分类
                AuthMenu::deleteAll(['id'=>$id]);
                AuthMenu::deleteAll(['parent'=>$id]);
            } else { //顶级分类
                $ids = AuthMenu::find()->where(['parent'=>$id])->select('id')->column();
                $data = AuthMenu::find()->where(['parent'=>$ids])->orWhere(['id'=>$ids])->select('id')->column();
                AuthMenu::deleteAll(['id'=>$id]);
                AuthMenu::deleteAll(['parent'=>$ids]);
            }
            $t->commit();
        } catch (\Exception $e) {
            $t->rollBack();
            throw new BadRequestHttpException($e->getMessage());
        }

    }
}
