<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\collection\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yuncms\db\ActiveRecord;
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
 * @property ActiveRecord $source
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
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
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
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne($this->model_class, ['id' => 'model_id']);
    }

    /**
     * 更新源模型计数器
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->source->updateCountersAsync(['collections' => 1]);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        $this->source->updateCountersAsync(['collections' => -1]);
        parent::afterDelete();
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
            'model_class' => $model,
            'model_id' => $modelId
        ])->exists();
    }
}