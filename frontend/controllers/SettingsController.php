<?php

namespace cms\user\frontend\controllers;

use Yii;
use yii\web\Controller;

class SettingsController extends Controller
{

	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'index' => 'cms\user\common\actions\Settings',
		];
	}

	/**
	 * Confirm e-mail
	 * @return void
	 */
	public function actionConfirm()
	{
		$user = Yii::$app->getUser();

		if ($user->isGuest)
			return $this->goHome();

		$object = $user->getIdentity();

		if ($object->sendConfirmEmail()) {
			Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Confirm message was sent on the specified E-mail.'));
		} else {
			Yii::$app->getSession()->setFlash('danger', Yii::t('user', 'Failed to send a confirm message on the specified e-mail.'));
		}

		return $this->redirect(['index']);
	}

}
