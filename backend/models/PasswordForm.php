<?php
namespace backend\models;
use yii\base\Model;

class PasswordForm extends Model{
    public $old_password;
    public $password;
    public $re_password;
    //重写验证规则
    public function rules()
    {
        return [
            [['password','re_password','old_password'],'required'],
            //['password','compare','compareAttribute'=>'re_password'],  //调用compare方法验证
        ];
    }
    public function attributeLabels()
    {
        return [
            'old_password'=>'旧密码',
            'password'=>'新密码',
            're_password'=>'确认密码',
        ];
    }

}