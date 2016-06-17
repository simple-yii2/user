<?php

namespace user\backend\assets;

use yii\web\AssetBundle;

class UserAsset extends AssetBundle {

	public $js = [
		'role.js',
	];
	
	public $depends = [
		'yii\web\JqueryAsset',
	];

	public function init()
	{
		parent::init();

		$this->sourcePath = dirname(__FILE__) . '/user';
	}

}
