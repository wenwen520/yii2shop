<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_login
 * @property string $last_ip
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    //用户角色
    public $roles=[];
    public static $status_options=[0=>'离线',1=>'在线'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }
    //获取所有的角色
    public static function getRolesOptions(){
        $authManager=Yii::$app->authManager;
        //获取所有的角色
        return ArrayHelper::map($authManager->getRoles(),'name','description');
    }
    //添加角色
    public function addRole($id){
        //关联角色
        $authManager=Yii::$app->authManager;
        foreach($this->roles as $roleName){
            //找到角色
            $role=$authManager->getRole($roleName);
            //关联
            if($role)$authManager->assign($role,$id);
        }
        return true;
    }
    //更新
    public function updateRole($id){
        $authManager=Yii::$app->authManager;
        //去掉所有与用户相关的角色
        $authManager->revokeAll($id);
        //关联
        foreach($this->roles as $roleName){
            //找到角色
            $role=$authManager->getRole($roleName);
            //关联
            if($role)$authManager->assign($role,$id);
        }
        return true;
    }
    //加载角色
    public function loadRole($id){
        //找到当前用户对应的角色
        $authManager=Yii::$app->authManager;
        $roles=$authManager->getRolesByUser($id);
        foreach($roles  as $role){
            $this->roles[]=$role->name;
        }
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password_hash', 'email'], 'required'],
            [['status', 'created_at'], 'integer'],
            [['username', 'password_hash', 'email', 'last_ip'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['auth_key'],'string'],
            [['email'],'email'],
            [['roles'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auth_key'=>'记住我',
            'username' => '用户名',
            'password_hash' => '密码',
            'email' => '邮箱',
            'status' => 'Status',
            'created_at' => 'Created At',
            'last_login' => 'Last Login',
            'last_ip' => 'Last Ip',
            'roles'=>'用户角色',
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        //通过ID获取用户
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        // TODO: Implement getId() method.
        //获取当前用户的ID
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
        return $this->getAuthKey()===$authKey;
    }
    public function generateAuthKey(){
        $this->auth_key=Yii::$app->security->generateRandomString();
    }
}
