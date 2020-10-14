<?php
namespace kaikaige\layui\actions;

use yii\base\Action;
use yii\web\UploadedFile;

class UploadAction extends Action
{
    public $dir;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->controller->enableCsrfValidation = false;
    }

    public function run()
    {
        $file = UploadedFile::getInstanceByName('file');
        $filename = sha1_file($file->tempName).'.'.$file->extension;
        if ($file->saveAs($filename)) {
            return $this->controller->asJson([
                'code' => 200,
                'location' => $filename,
                'msg' => '上传成功'
            ]);
        } else {
            return $this->controller->asJson([
                'code' => 400,
                'data' => $filename,
                'msg' => '图片上传错误，错误代码['.$file->error.']'
            ]);
        }
    }
}