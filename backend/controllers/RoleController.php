<?php

namespace user\backend\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

use user\backend\models\RoleForm;
use user\common\models\User;

/**
 * Role manage controller
 */
class RoleController extends Controller {

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
	 * Predefined actions
	 * @return array
	 */
	public function actions() {
		return [
			'users'=>'user\common\actions\AutoComplete',
		];
	}

	/**
	 * Role list
	 * @return void
	 */
	public function actionIndex() {
		$items = Yii::$app->authManager->getRoles();
		unset($items['author']);

		$dataProvider = new ArrayDataProvider([
			'allModels'=>$items,
			'pagination'=>false,
		]);

		return $this->render('index', [
			'dataProvider'=>$dataProvider,
		]);
	}

	/**
	 * Role creating
	 * @return void
	 */
	public function actionCreate() {
		$model = new RoleForm;

		if ($model->load(Yii::$app->request->post()) && $model->create()) {
			Yii::$app->session->setFlash('success', Yii::t('user', 'Changes saved successfully.'));
			return $this->redirect(['index']);
		}

		return $this->render('create', [
			'model'=>$model,
		]);
	}

	/**
	 * Role editting
	 * @param string $name Role name
	 * @return void
	 */
	public function actionUpdate($name) {
		$item = Yii::$app->authManager->getRole($name);
		if ($item === null) throw new BadRequestHttpException(Yii::t('user', 'Role was not found.'));

		$model = new RoleForm(['item'=>$item]);
		if ($model->load(Yii::$app->request->post()) && $model->update()) {
			Yii::$app->session->setFlash('success', Yii::t('user', 'Changes saved successfully.'));
			return $this->redirect(['index']);
		}

		return $this->render('update', [
			'model'=>$model,
		]);
	}

	/**
	 * Role deleting
	 * @param string $name Role name
	 * @return void
	 */
	public function actionDelete($name) {
		$auth = Yii::$app->authManager;

		$item = $auth->getRole($name);
		if ($item === null) throw new BadRequestHttpException(Yii::t('user', 'Role was not found.'));

		if ($auth->remove($item)) Yii::$app->session->setFlash('success', Yii::t('user', 'Role deleted successfully.'));

		return $this->redirect(['index']);
	}

	/**
	 * User role assignment
	 * @param string $email User email
	 * @return void
	 */
	public function actionAssign($email) {
		//user
		$user = User::findByEmail($email);
		if ($user === null) return Json::encode([
			'error'=>Yii::t('user', 'User not found.'),
		]);

		$model = new RoleForm(['users'=>[$user->id]]);
		return Json::encode([
			'content'=>$this->renderPartial('form/assignment', ['model'=>$model]),
		]);
	}

}
