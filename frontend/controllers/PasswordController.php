<?php

namespace cms\users\frontend\controllers;

use Yii;
use yii\web\Controller;

use cms\users\frontend\models\PasswordChangeForm;

/**
 * Password change controller
 */
class PasswordController extends Controller
{

	/**
	 * Password change
	 * @return void
	 */
	public function actionIndex()
	{
		$user = Yii::$app->getUser();

		//check login
		if ($user->isGuest)
			return $user->loginRequired();

		//model
		$model = new PasswordChangeForm($user->getIdentity());

		//read user data
		if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
			Yii::$app->getSession()->setFlash('success', Yii::t('users', 'The new password has been set.'));

			return $this->redirect(['settings/index']);
		}

		return $this->render('index', [
			'model' => $model,
		]);
	}

}
