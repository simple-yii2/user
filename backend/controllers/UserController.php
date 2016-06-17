<?php

namespace user\backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

use user\backend\models\UserForm;
use user\backend\models\UserSearch;
use user\common\models\User;

/**
 * User manage controller
 */
class UserController extends Controller {

	/**
	 * Access control
	 * @return array
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					['allow' => true, 'roles' => ['admin']],
				],
			],
		];
	}

	/**
	 * User list
	 * @return void
	 */
	public function actionIndex() {
		$model = new UserSearch;

		return $this->render('index', [
			'dataProvider'=>$model->search(Yii::$app->request->get()),
			'model'=>$model,
		]);
	}

	/**
	 * User editing
	 * @param integer $id User id
	 * @return void
	 */
	public function actionUpdate($id) {
		$item = User::findOne($id);
		if ($item === null) throw new BadRequestHttpException(Yii::t('user', 'User not found.'));

		$model = new UserForm(['item'=>$item]);
		if ($model->load(Yii::$app->request->post()) && $model->update()) {
			Yii::$app->session->setFlash('success', Yii::t('user', 'Changes saved successfully.'));
			return $this->redirect(['index']);
		}

		return $this->render('update', [
			'model'=>$model,
		]);
	}

}
