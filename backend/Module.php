<?php

namespace user\backend;

use Yii;

use user\common\components\AuthorRule;
use user\common\models\User;

/**
 * User backend module
 */
class Module extends \yii\base\Module {

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->checkDatabase();
		$this->addTranslation();
	}

	/**
	 * Database checking
	 * @return void
	 */
	protected function checkDatabase()
	{
		//schema
		$db = Yii::$app->db;
		$filename = dirname(__DIR__) . '/schema/' . $db->driverName . '.sql';
		$sql = explode(';', file_get_contents($filename));
		foreach ($sql as $s) {
			if (trim($s) !== '')
				$db->createCommand($s)->execute();
		}

		//rbac
		$auth = Yii::$app->getAuthManager();
		if ($auth->getRole('author') === null) {
			//author role
			$author = $auth->createRole('author');
			$auth->add($author);
			//author rule
			$rule = new AuthorRule;
			$auth->add($rule);
			//author permission
			$own = $auth->createPermission('own');
			$own->ruleName = $rule->name;
			$auth->add($own);
			//add permission with rule to role
			$auth->addChild($author, $own);
		}

		//data
		if (User::find()->andWhere(['admin'=>true, 'active'=>true])->count() == 0) {
			$model = new User([
				'admin'=>true,
				'active'=>true,
				'email'=>'admin',
			]);
			$model->setPassword('admin');
			$model->save();
		}
	}

	/**
	 * Adding translation to i18n
	 * @return void
	 */
	protected function addTranslation()
	{
		Yii::$app->i18n->translations['user'] = [
			'class'=>'yii\i18n\PhpMessageSource',
			'sourceLanguage'=>'en-US',
			'basePath'=>'@user/messages',
		];
	}

	/**
	 * Making main menu item of module
	 * @return array
	 */
	public function getMenuItem()
	{
		if (Yii::$app->user->can('admin')) {
			return [
				['label' => Yii::t('user', 'Security'), 'items' => [
					['label' => Yii::t('user', 'Permissions'), 'url' => ['/user/permission/index']],
					['label' => Yii::t('user', 'Roles'), 'url' => ['/user/role/index']],
					['label' => Yii::t('user', 'Users'), 'url' => ['/user/user/index']],
				]],
			];
		}
		
		return [];
	}

}
