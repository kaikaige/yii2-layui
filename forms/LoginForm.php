<?php
namespace kaikaige\layui\forms;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $loginCount; //允许登录失败次数，超过次数冷却30分钟
    public $rememberExpire; //记住登录状态最大时间默认一周

    public $username;
    public $password;
    public $captcha;
    public $code;
    public $rememberMe = true;
    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
//            ['captcha', 'captcha'],
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
        if (!$this->hasErrors()) {
            if ($this->loginCount > 0) {
                $c = Yii::$app->cache->get('layui.admin.login.count');
                if ($c >= $this->loginCount) {
                    return $this->addError($attribute, '错误太多请稍后尝试登录');
                }
            }

            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                if ($this->loginCount > 0) {
                    $c++;
                    Yii::$app->cache->set('layui.admin.login.count', $c, 60 * 1800);
                }
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? $this->rememberExpire : 0);
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
            $this->_user = $userClass::findByUsername($this->username);
        }

        return $this->_user;
    }
}
