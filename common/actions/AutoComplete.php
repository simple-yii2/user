<?php

namespace cms\users\common\actions;

use yii\base\Action;
use yii\helpers\Html;
use yii\helpers\Json;

use cms\users\common\models\User;

/**
 * User autocomlete action
 */
class AutoComplete extends Action {

	/**
	 * @var boolean Administrators include in search results if true
	 */
	public $admin = false;

	/**
	 * @var integer Result count
	 */
	public $limit = 8;

	public function run($term) {
		//query conditions
		$query = User::find()->andFilterWhere(['like', 'email', $term]);
		if (!$this->admin) $query->andWhere(['not', ['admin' => true]]);

		//get items
		$rows = $query->limit($this->limit)->all();

		//make autocomplete array
		$items = array_map(function($v) {
			$html = Html::encode($v->email);
			$username = $v->username;
			if ($username !== $v->email) $html .= ' '.Html::tag('span', Html::encode($username), ['class' => 'text-muted']);
			return ['label' => $v->email, 'value' => $v->email, 'id' => (string) $v->id, 'html' => $html];
		}, $rows);

		return Json::encode($items);
	}

}
