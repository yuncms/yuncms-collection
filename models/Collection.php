<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\collection\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yuncms\user\models\User;

/**
 * This is the model class for table "attentions".
 *
 * @property integer $user_id
 * @property string $follow_id
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
        return [TimestampBehavior::className()];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'model_id', 'model'], 'required'],
            [['model', 'subject'], 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     *
     * @param string $model Name of model
     * @return integer|false Id corresponding model or false if matches not found
     */
    public static function getNameByModel($model)
    {
        if (null !== $models = Yii::$app->getModule('collection')->models) {
            foreach ($models as $key => $value) {
                if (is_string($value) && $value == $model) {
                    return $key;
                } else if ((is_array($value) && isset($value['model'])) && $value['model'] == $model) {
                    return $key;
                }
            }
        }
        return false;
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

    public static function create($attribute)
    {
        $model = new static ($attribute);
        if ($model->save()) {
            return $model;
        }
        return false;
    }
}