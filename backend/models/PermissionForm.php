<?php
namespace backend\models;

use yii\base\Model;

class PermissionForm extends Model{
    public $name;
    public $description;
    //重写验证方法
    public function rules()
    {
        return [
            [['name','description'],'required'],
        ];
    }
    //定义属性标签
    public function attributeLabels()
    {
        return [
            'name'=>'权限名',
            'description'=>'描述',
        ];
    }
    //添加权限
    public function addPermission(){
        $authManager=\Yii::$app->authManager;
        //判断该权限是否已存在
        if($authManager->getPermission($this->name)){
            $this->addError('name','该权限已存在');
        }else{
            //新建权限
            $permission=$authManager->createPermission($this->name);
            $permission->description=$this->description;
            //保存
            return $authManager->add($permission);
        }
        return false;
    }
    //更新加载数据
    public function loadPermission($permission){
        $this->name=$permission->name;
        $this->description=$permission->description;
    }
    //更新权限
    public function updatePermission($name){
        //获取要修改的权限
        $authManager=\Yii::$app->authManager;
        $permission=$authManager->getPermission($name);
        //判断修改后的权限是否已存在
        if($this->name !=$name && $authManager->getPermission($this->name)){
            $this->addError('name','该权限已存在');
        }else{//更新权限
            $permission->name=$this->name;
            $permission->description=$this->description;
            return $authManager->update($name,$permission);
        }
        return false;

    }
}