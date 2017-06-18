<?php
namespace backend\models;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;

class RoleForm extends Model{
    //角色名
    public $name;
    //角色描述
    public $description;
    //权限
    public $permission=[];
    public function rules()
    {
        return [
            [['name','description'],'required'],
            [['permission'],'safe']
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'角色名',
            'description'=>'描述',
            'permission'=>'权限',
        ];
    }
    //新建添加角色
    public function addRole(){
        $authManager=\Yii::$app->authManager;
        //判断角色是否已经存在
        if($authManager->getRole($this->name)){
            $this->addError('name','角色已经存在');
        }else{
            $role=$authManager->createRole($this->name);
            $role->description=$this->description;
            //添加角色
            if($authManager->add($role)){
                //循环取出所有的权限
                foreach ($this->permission as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    //关联权限
                    if($permission)$authManager->addChild($role,$permission);
                }
                return true;
            }
        }
        return false;
    }
    //新建获取所有权限
    public static function getPermissionOptions(){
        $authManager=\Yii::$app->authManager;
        //获取所有的权限
        return $permissions=ArrayHelper::map($authManager->getPermissions(),'name','description');
    }
    //加载角色
    public function loadRole(Role $role){
        $this->name=$role->name;
        $this->description=$role->description;
        //将该用户的权限读取出来，加载
        foreach(\Yii::$app->authManager->getPermissionsByRole($role->name) as $permission){
            $this->permission[]=$permission->name;
        }
    }
    //更新角色
    public function updateRole($name,$role){
        $authManager=\Yii::$app->authManager;
        $role->name=$this->name;
        $role->description=$this->description;
        //如果角色名被修改，判断角色名是否已经存在
        if($name!=$this->name && $authManager->getRole($this->name)){
            $this->addError('name','该角色已经存在');
        }else{
            if($authManager->update($name,$role)){
                //先去掉所有的权限
                $authManager->removeChildren($role);
                //再关联修改的权限
                foreach ($this->permission as $permissionName){
                    //查找权限
                    $permission=$authManager->getPermission($permissionName);
                    $authManager->addChild($role,$permission);
                }
                return true;
            }
        }
        return false;

    }
}