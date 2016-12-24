<?php

namespace cms\users\frontend;

use Yii;

class Module extends \yii\base\Module
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		self::addTranslation();
	}

	/**
	 * Adding translation to i18n
	 * @return void
	 */
	protected static function addTranslation()
	{
		if (!isset(Yii::$app->i18n->translations['users'])) {
			Yii::$app->i18n->translations['users'] = [
				'class' => 'yii\i18n\PhpMessageSource',
				'sourceLanguage' => 'en-US',
				'basePath' => dirname(__DIR__) . '/messages',
			];
		}
	}

}
