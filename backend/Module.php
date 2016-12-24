<?php

namespace cms\users\backend;

use Yii;

use cms\users\common\components\AuthorRule;
use cms\users\common\models\User;

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
		if (!isset(Yii::$app->i18n->translations['users'])) {
			Yii::$app->i18n->translations['users'] = [
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

		if (Yii::$app->user->can('admin')) {
			return [
				['label' => Yii::t('users', 'Security'), 'items' => [
					['label' => Yii::t('users', 'Permissions'), 'url' => ["$base/users/permission/index"]],
					['label' => Yii::t('users', 'Roles'), 'url' => ["$base/users/role/index"]],
					'<li role="separator" class="divider"></li>',
					['label' => Yii::t('users', 'Users'), 'url' => ["$base/users/user/index"]],
				]],
			];
		}
		
		return [];
	}

}
