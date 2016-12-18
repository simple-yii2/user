<?php

namespace cms\user\backend\models;

use Yii;
use yii\data\ActiveDataProvider;

use cms\user\common\models\User;

/**
 * User search model
 */
class UserSearch extends User
{

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'email' => Yii::t('user', 'E-mail'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['email', 'string'],
		];
	}

	/**
	 * Search function
	 * @param array $params Attributes array
	 * @return \yii\data\ActiveDataProvider
	 */
	public function search($params) {
		//ActiveQuery
		$query = static::find()->andWhere(['not', ['email' => 'admin']]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		//return data provider if no search
		if (!($this->load($params) && $this->validate())) return $dataProvider;

		//search
		$query->andFilterWhere(['like', 'email', $this->email]);

		return $dataProvider;
	}

}
