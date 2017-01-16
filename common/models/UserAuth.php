<?php

namespace cms\user\common\models;

use yii\db\ActiveRecord;

class UserAuth extends ActiveRecord
{

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'UserAuth';
	}

	/**
	 * User relation
	 * @return yii\db\ActiveQueryInterface
	 */
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}

}
