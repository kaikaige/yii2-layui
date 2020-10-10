<?php

namespace kaikaige\layui\controllers;

use kaikaige\layui\components\SearchModel;
use Yii;
use kaikaige\layui\models\SysConfigCate;
use yii\data\ActiveDataProvider;
use kaikaige\layui\base\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SysConfigCateController implements the CRUD actions for SysConfigCate model.
 */
class SysConfigCateController extends Controller
{
    protected $modelClass = SysConfigCate::class;
}
