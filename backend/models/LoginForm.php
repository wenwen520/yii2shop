<?php
namespace backend\models;
use yii\base\Model;

class LoginForm extends Model {
    //name属性
    public $username;
    //密码属性
    public $password;
    //验证码属性
    public $code;
    //重写验证法方法
    public $cookie;
    public function rules()
    {
        return [
            [['username','password'],'required'],
            //添加自定义验证方法
            ['username','nameValidate'],
            ['code','captcha','captchaAction'=>'user/captcha'],
            [['cookie'],'safe'],
        ];
    }
    //重写标签属性
    public function attributeLabels()
    {
        return [
            'username'=>'会员名',
            'password'=>'密码',
            'code'=>'验证码',
            'cookie'=>'记住我'
        ];
    }
    //新建自定义验证方法
    public function nameValidate(){
        //查询数据库
        $user=User::findOne(['username'=>$this->username]);
        //验证用户名
        if($user){
            //验证密码
            if(!\Yii::$app->security->validatePassword($this->password,$user->password_hash)){
                $this->addError('username','用户名或密码错误');
            }else{
                $user->generateAuthKey();
                $user->save(false);
                $cookie=\Yii::$app->user->authTimeout;
                //验证成功，登录
                \Yii::$app->user->login($user,$this->cookie?$cookie:0);
            }
        }else{
            //验证失败
            $this->addError('username','用户名或密码错误');
        }
    }
}