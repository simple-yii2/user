<?php

namespace user\backend\models;

use Yii;
use yii\data\ActiveDataProvider;

use user\common\models\User;

/**
 * User search model
 */
class UserSearch extends User {

	/**
	 * Search rules
	 * @return array
	 */
	public function rules() {
		return [
			['email', 'string'],
		];
	}

	/**
	 * Search function
	 * @param array $params Attributes array
	 * @return yii\data\ActiveDataProvider
	 */
	public function search($params) {
		//ActiveQuery
		$query = User::find()->andWhere(['not', ['id' => Yii::$app->getUser()->id]]);

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
