<?php

namespace cms\user\frontend\assets;

use yii\web\AssetBundle;

class AuthChoiceAsset extends AssetBundle
{

	public $css = [
		'auth-choice.css',
	];

	public $depends = [
		'yii\authclient\widgets\AuthChoiceAsset',
	];

	public function init()
	{
		parent::init();

		$this->sourcePath = __DIR__ . '/auth-choice';
	}

}
