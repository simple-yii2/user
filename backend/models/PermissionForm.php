<?php

namespace cms\user\backend\models;

use Yii;

/**
 * Permission form
 */
class PermissionForm extends RbacForm
{

	/**
	 * @var boolean Is own permission
	 */
	public $own = false;

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), [
			'own' => Yii::t('user', 'Allow to author'),
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return array_merge(parent::rules(), [
			['own', 'boolean'],
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		
		if ($this->item !== null) {
			$auth = Yii::$app->authManager;
			$ownItem = $auth->getPermission('own');
			if ($ownItem && $auth->hasChild($ownItem, $this->item)) $this->own = true;
		}
	}

	/**
	 * Permission creation
	 * @return boolean
	 */
	public function create()
	{
		if (!$this->validate()) return false;

		$auth = Yii::$app->authManager;

		$this->item = $auth->createPermission($this->name);
		$this->item->description = $this->description;

		if (!$auth->add($this->item)) return false;

		return $this->updateRelative();
	}

	/**
	 * Permission updating
	 * @return boolean
	 */
	public function update()
	{
		if (parent::update() === false) return false;

		return $this->updateRelative();
	}

	/**
	 * Update relative
	 * @return boolean
	 */
	public function updateRelative()
	{
		$auth = Yii::$app->authManager;

		//allow to author
		$ownItem = $auth->getPermission('own');
		if ($ownItem) {
			$oldOwn = $auth->hasChild($ownItem, $this->item);
			if ($this->own) {
				if (!$oldOwn) $auth->addChild($ownItem, $this->item);
			} else {
				if ($oldOwn) $auth->removeChild($ownItem, $this->item);
			}
		}

		return true;
	}

}
