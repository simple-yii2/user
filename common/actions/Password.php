<?php

namespace cms\user\common\actions;

use Yii;
use yii\base\Action;

use cms\user\common\models\PasswordChangeForm;

/**
 * Password change action
 */
class Password extends Action {

	/**
	 * @inheritdoc
	 */
	public function run() {
		$user = Yii::$app->getUser();

		//check login
		if ($user->isGuest)
			return $user->loginRequired();

		//model
		$model = new PasswordChangeForm($user->getIdentity());

		//read user data
		if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
			Yii::$app->getSession()->setFlash('success', Yii::t('user', 'The new password has been set.'));
			return $this->controller->goBack();
		}

		return $this->controller->render('index', [
			'model' => $model,
		]);
	}

}
