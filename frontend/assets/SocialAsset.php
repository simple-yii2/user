<?php

namespace cms\user\frontend\assets;

use yii\web\AssetBundle;

class SocialAsset extends AssetBundle
{

	public $css = [
		'social.css',
	];

	public function init()
	{
		parent::init();

		$this->sourcePath = __DIR__ . '/social';
	}

}
