<?php

namespace cms\user\backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

use cms\user\backend\models\PasswordForm;
use cms\user\backend\models\UserForm;
use cms\user\backend\models\UserSearch;
use cms\user\common\models\User;

/**
 * User manage controller
 */
class UserController extends Controller
{

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
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
	 * List
	 * @return string
	 */
	public function actionIndex()
	{
		$model = new UserSearch;

		return $this->render('index', [
			'dataProvider' => $model->search(Yii::$app->getRequest()->get()),
			'model' => $model,
		]);
	}

	/**
	 * Create
	 * @return string
	 */
	public function actionCreate()
	{
		$object = new User;

		$model = new UserForm($object, ['scenario' => 'create']);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('user', 'Changes saved successfully.'));
			return $this->redirect(['index']);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Update
	 * @param integer $id 
	 * @return string
	 */
	public function actionUpdate($id)
	{
		$object = User::findOne($id);
		if ($object === null)
			throw new BadRequestHttpException(Yii::t('user', 'User not found.'));

		$model = new UserForm($object);
		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('user', 'Changes saved successfully.'));
			return $this->redirect(['index']);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * Set password
	 * @param integer $id 
	 * @return string
	 */
	public function actionPassword($id)
	{
		$object = User::findOne($id);
		if ($object === null)
			throw new BadRequestHttpException(Yii::t('user', 'User not found.'));

		$model = new PasswordForm($object);
		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('user', 'Password set successfully.'));
			return $this->redirect(['index']);
		}

		return $this->render('password', [
			'model' => $model,
		]);
	}

}
