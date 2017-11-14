<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\collection\frontend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yuncms\user\models\User;

/**
 * Class SpaceController
 * @package yuncms\collection\frontend\controllers
 */
class SpaceController extends Controller
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['?', '@']
                    ]
                ]
            ]
        ];
    }

    public $collectionClassMaps = [
        'questions' => 'yuncms\question\models\Question',
        'articles' => 'yuncms\article\models\Article',
        'lives' => 'yuncms\live\models\Stream',
        'notes' => 'yuncms\note\models\Note',
    ];

    /**
     * 查看收藏
     * @param int $id
     * @param string $type 类别
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex($id, $type = 'notes')
    {
        $model = $this->findModel($id);
        if (!isset($this->collectionClassMaps[$type])) {
            throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
        }
        $query = $model->getCollections()->andWhere(['model' => $this->collectionClassMaps[$type]])->orderBy(['created_at' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('index', [
            'model' => $model,
            'type' => $type,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
        }
    }
}