<?php
namespace kaikaige\layui\forms;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    const MAX_LOGIN_COUNT = 3;

    public $username;
    public $password;
    public $code;
    public $rememberMe = true;

    public $loginCount;

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
            $c = Yii::$app->session->get('login_count', 0);
            if ($c > self::MAX_LOGIN_COUNT) {
                return $this->addError('login_count', '错误太多请稍后尝试登录');
            }
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $c++;
                Yii::$app->session->set('login_count', $c);
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
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
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
