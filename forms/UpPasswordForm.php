<?php
namespace kaikaige\layui\forms;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class UpPasswordForm extends Model
{
    public $oldPassword;
    public $newPassword;
    public $rePassword;

    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['oldPassword', 'newPassword', 'rePassword'], 'required'],
            // password is validated by validatePassword()
            ['oldPassword', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        $user = $this->getUser();
        if (!$user->validatePassword($this->oldPassword)) {
            return $this->addError('oldPassword', '原始密码错误');
        }

        if ($this->newPassword != $this->rePassword) {
            return $this->addError('newPassword', '两次输入密码不一致');
        }
    }

    public function update()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $user->setPassword($this->newPassword);
            return $user->save();
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $userClass = Yii::$app->user->identityClass;
            $this->_user = $userClass::findByUsername(Yii::$app->user->identity->username);
        }

        return $this->_user;
    }
}
