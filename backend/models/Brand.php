<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{
    //定义上传文件对象
    public $imgFile;
    //定义验证码对象
    public $code;
    //定义场景常量
    const SCENARIO_ADD='add';
    const SCENARIO_UPDATE='update';
    public function scenarios()
    {
        $scenarios= parent::scenarios();
        $scenarios[self::SCENARIO_ADD]=['name','intro','sort','imgFile','code','status'];
        $scenarios[self::SCENARIO_UPDATE]=['name','intro','imgFile','sort','status'];
        return $scenarios;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['intro'], 'string'],
            [['sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['logo'], 'string', 'max' => 255],
            ['code','captcha','captchaAction'=>'brand/captcha'],
            ['imgFile','file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>false,'on'=>self::SCENARIO_ADD],
            ['imgFile','file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>true,'on'=>self::SCENARIO_UPDATE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'logo' => 'LOGO',
            'sort' => '排序',
            'status' => '状态',
            'code'=>'验证码',
        ];
    }
}
