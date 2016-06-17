<?php

namespace app\modules\user\frontend\controllers;

use Yii;
use yii\web\Controller;

/**
 * User settings controller
 */
class SettingsController extends Controller {

	/**
	 * Settings editting
	 * @return void
	 */
	public function actionIndex() {
		if (Yii::$app->user->isGuest) return $this->goHome();

		$model = Yii::$app->user->identity;
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Changes saved successfully.'));
		}

		return $this->render('index', ['model'=>$model]);
	}

	/**
	 * Confirm e-mail
	 * @return void
	 */
	public function actionConfirm() {
		if (Yii::$app->user->isGuest) return $this->goHome();

		if (Yii::$app->user->identity->sendConfirmEmail()) {
			Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Confirm message was sent on the specified E-mail.'));
		} else {
			Yii::$app->getSession()->setFlash('danger', Yii::t('user', 'Failed to send a confirm message on the specified e-mail.'));
		}

		return $this->redirect(['index']);
	}

}
