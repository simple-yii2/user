<?php

namespace user\backend\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

use user\backend\models\PermissionForm;

/**
 * Premission manage controller
 */
class PermissionController extends Controller {

	/**
	 * Access control
	 * @return array
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					['allow' => true, 'roles' => ['Admin']],
				],
			],
		];
	}

	/**
	 * Permission list
	 * @return void
	 */
	public function actionIndex() {
		$items = Yii::$app->authManager->getPermissions();
		unset($items['own']);

		$dataProvider = new ArrayDataProvider([
			'allModels' => $items,
			'pagination' => false,
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Permission creating
	 * @return void
	 */
	public function actionCreate() {
		$model = new PermissionForm;
		if ($model->load(Yii::$app->request->post()) && $model->create()) {
			return $this->redirect(['index']);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Permission editting
	 * @param string $name Permission name
	 * @return void
	 */
	public function actionUpdate($name) {
		$item = Yii::$app->authManager->getPermission($name);
		if ($item === null) throw new BadRequestHttpException(Yii::t('user', 'Premission was not found.'));

		$model = new PermissionForm(['item' => $item]);
		if ($model->load(Yii::$app->request->post()) && $model->update()) {
			Yii::$app->session->setFlash('success', Yii::t('Changes saved successfully.'));
			return $this->redirect(['index']);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * Permission deleting
	 * @param string $name Permission name
	 * @return void
	 */
	public function actionDelete($name) {
		$auth = Yii::$app->authManager;
		
		$item = $auth->getPermission($name);
		if ($item === null) throw new BadRequestHttpException(Yii::t('user', 'Premission was not found.'));

		if ($auth->remove($item)) Yii::$app->session->setFlash('success', Yii::t('user', 'Premission deleted successfully.'));

		return $this->redirect(['index']);
	}

}
