<?php

namespace user\backend\assets;

use yii\web\AssetBundle;

class UserAsset extends AssetBundle {

	public $sourcePath = __DIR__ . '/user';

	public $js = [
		'role.js',
	];
	
	public $depends = [
		'yii\web\JqueryAsset',
	];

}
