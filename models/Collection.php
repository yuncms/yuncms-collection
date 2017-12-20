<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\collection\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yuncms\user\models\User;

/**
 * This is the model class for table "collections".
 *
 * @property integer $user_id
 * @property integer $model_id
 * @property string $model_class
 * @property string $subject
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class Collection extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%collections}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => BlameableBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
                ],
            ]];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id'], 'required'],
            [['subject'], 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * 是否收藏
     * @param string $model
     * @param integer $modelId
     * @param integer $user_id
     * @return bool
     */
    public static function isCollected($model, $modelId, $user_id = null)
    {
        return static::find()->where([
            'user_id' => $user_id ? $user_id : Yii::$app->user->getId(),
            'model' => $model,
            'model_id' => $modelId
        ])->exists();
    }

    /**
     * 快速创建对象
     * @param array $attribute
     * @param boolean $runValidation
     * @return bool|static
     */
    public static function create($attribute, $runValidation = true)
    {
        $model = new static ($attribute);
        if ($model->save($runValidation)) {
            return $model;
        }
        return false;
    }
}