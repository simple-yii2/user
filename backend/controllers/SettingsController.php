<?php

namespace cms\user\backend\controllers;

use yii\web\Controller;

class SettingsController extends Controller
{

	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
			'index' => 'cms\user\common\actions\Settings',
		];
	}

}
