<?php

namespace cms\user\common\actions;

use Yii;
use yii\base\Action;

use cms\user\common\models\SettingsForm;

/**
 * User settings action
 */
class Settings extends Action {

	/**
	 * @inheritdoc
	 */
	public function run() {
		$user = Yii::$app->getUser();

		if ($user->isGuest)
			return $this->controller->goHome();

		$model = new SettingsForm($user->getIdentity());

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Changes saved successfully.'));
			return $this->controller->refresh();
		}

		return $this->controller->render('index', [
			'model' => $model,
		]);
	}

}
