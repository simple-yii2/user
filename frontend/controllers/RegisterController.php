<?php

namespace cms\user\frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\Controller;

use cms\user\common\models\User;
use cms\user\frontend\models\RegisterForm;

/**
 * Register controller
 */
class RegisterController extends Controller
{

	/**
	 * Registration
	 * @return void
	 */
	public function actionIndex()
	{
		if (!Yii::$app->user->isGuest)
			return $this->redirect(['settings/index']);

		$model = new RegisterForm(new User);
		if ($model->load(Yii::$app->request->post()) && $model->register()) {

			$message = Yii::t('user', 'Registration completed successfully.');
			if ($model->sendEmail()) {
				Yii::$app->getSession()->setFlash('success', $message . ' ' . Yii::t('user', 'Confirm message was sent on the specified E-mail.'));
			} else {
				Yii::$app->getSession()->setFlash('warning', $message . ' ' . Yii::t('user', 'Failed to send a confirm message on the specified e-mail.'));
			}

			return $this->goHome();
		}

		return $this->render('index', [
			'model' => $model,
		]);
	}

	/**
	 * E-mail confirm
	 * @param string $token E-mail confirm token
	 * @return void
	 */
	public function actionConfirm($token)
	{
		$user = User::findByConfirmToken($token);

		if ($user === null)
			throw new InvalidParamException(Yii::t('user', 'Link invalid. Perhaps, e-mail has already been confirmed or the waiting period has expired.'));

		$user->confirmed = true;
		$user->removeConfirmToken();

		if ($user->save()) {
			Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Your e-mail is successfully confirmed.'));
		} else {
			Yii::$app->getSession()->setFlash('error', Yii::t('user', 'An error occurred while trying to confirm the e-mail.'));
		}

		return $this->redirect(['settings/index']);
	}

}
