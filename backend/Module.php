<?php

namespace cms\user\backend;

use Yii;
use yii\helpers\Html;

use cms\user\common\components\AuthorRule;
use cms\user\common\models\User;

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
		self::addTranslation();
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
		if (User::find()->andWhere(['admin' => true, 'active' => true])->count() == 0) {
			$model = new User([
				'admin' => true,
				'active' => true,
				'email' => 'admin',
			]);
			$model->setPassword('admin');
			$model->save();
		}
	}

	/**
	 * Adding translation to i18n
	 * @return void
	 */
	protected static function addTranslation()
	{
		if (!isset(Yii::$app->i18n->translations['user'])) {
			Yii::$app->i18n->translations['user'] = [
				'class' => 'yii\i18n\PhpMessageSource',
				'sourceLanguage' => 'en-US',
				'basePath' => dirname(__DIR__) . '/messages',
			];
		}
	}

	/**
	 * Making module menu.
	 * @param string $base route base
	 * @return array
	 */
	public static function getMenu($base)
	{
		self::addTranslation();

		if (Yii::$app->user->can('admin')) {
			return [
				['label' => Yii::t('user', 'Security'), 'items' => [
					['label' => Yii::t('user', 'Permissions'), 'url' => ["$base/user/permission/index"]],
					['label' => Yii::t('user', 'Roles'), 'url' => ["$base/user/role/index"]],
					'<li role="separator" class="divider"></li>',
					['label' => Yii::t('user', 'Users'), 'url' => ["$base/user/user/index"]],
				]],
			];
		}
		
		return [];
	}

	/**
	 * Making user module menu.
	 * @param string $base route base
	 * @return array
	 */
	public static function getUserMenu($base)
	{
		self::addTranslation();

		if (Yii::$app->user->isGuest)
			return [];

		$name = Html::encode(Yii::$app->getUser()->getIdentity()->getUsername());

		return [
			[
				'label' => '<span class="glyphicon glyphicon-user"></span>&nbsp;' . $name,
				'encode' => false,
				'items' => [
					['label' => Yii::t('user', 'Settings'), 'url' => ["$base/user/settings/index"]],
					['label' => Yii::t('user', 'Change password'), 'url' => ["$base/user/password/index"]],
					'<li role="separator" class="divider"></li>',
					['label' => Yii::t('user', 'Logout'), 'url' => ["$base/user/logout/index"]],
				],
			],
		];
	}

}
