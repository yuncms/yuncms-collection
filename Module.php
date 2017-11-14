<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\collection;


use Yii;
use yii\base\InvalidConfigException;

/**
 * Class Module
 *
 * @property array $models
 * @package yuncms\credit
 */
class Module extends \yii\base\Module
{
    /**
     * Matching models with integer id's
     * @var array
     */
    public $models;

    /**
     * 初始化
     */
    public function init()
    {
        parent::init();
        if (!isset($this->models)) {
            throw new InvalidConfigException('models not configurated');
        }
        $this->registerTranslations();
    }

    /**
     * 注册语言包
     * @return void
     */
    public function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['collection*'])) {
            Yii::$app->i18n->translations['collection*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => __DIR__ . '/messages',
            ];
        }
    }
}
