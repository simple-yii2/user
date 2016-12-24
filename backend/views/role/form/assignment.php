<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

use cms\user\backend\assets\UserAsset;
use cms\user\common\models\User;

//js
UserAsset::register($this);

//users
$dataProvider = new ArrayDataProvider([
	'allModels' => User::findAll($model->users),
	'pagination' => false,
]);

//attribute name
$name = Html::getInputName($model, 'users').'[]';

?>
<fieldset>

	<div class="form-group">
		<div class="col-sm-8 col-md-6">
			<div class="input-group">
				<?= AutoComplete::widget([
					'name' => 'email',
					'options' => ['class' => 'form-control'],
					'clientOptions' => [
						'source' => Url::toRoute('users'),
						'create' => new JsExpression('function(event, ui) {
							$("#role-form input.ui-autocomplete-input").autocomplete("instance")._renderItem = function(ul, item) {
								return $("<li>").html(item.html).appendTo(ul);
							};
						}'),
					],
				]) ?>
				<span class="input-group-btn">
					<?= Html::a(Yii::t('user', 'Add'), ['assign'], ['class' => 'btn btn-default assign', 'disabled' => true]) ?>
				</span>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-8 col-md-6">
			<?= Html::hiddenInput(Html::getInputName($model, 'users'), '') ?>
			<?= GridView::widget([
				'id' => 'role-users',
				'dataProvider' => $dataProvider,
				'emptyText' => false,
				'summary' => '',
				'showHeader' => false,
				'tableOptions' => ['class' => 'table table-condensed'],
				'rowOptions' => function($model, $key, $index, $grid) {
					return ['data-id' => $model->id];
				},
				'columns' => [
					[
						'header' => Yii::t('user', 'Name'),
						'format' => 'raw',
						'value' => function($model, $key, $index, $column) use ($name) {
							$r = Html::hiddenInput($name, $model->id);
							$r .= '<span class="glyphicon glyphicon-user"></span>';
							$r .= ' '.Html::encode($model->email);
							$s = $model->username;
							if ($s !== $model->email)
								$r .= ' '.Html::tag('span', Html::encode('('.$s.')'), ['class' => 'text-muted']);

							return $r;
						},
					],
					[
						'class' => 'yii\grid\ActionColumn',
						'options' => ['style' => 'width: 25px;'],
						'buttons' => [
							'remove' => function($url, $model, $key) {
								return Html::a('<span class="glyphicon glyphicon-remove"></span>', '#', [
									'class' => 'revoke',
									'title' => Yii::t('user', 'Delete'),
									'data-pjax' => '0',
								]);
							},
						],
						'template' => '{remove}',
					],
				],
			]) ?>
		</div>
	</div>

</fieldset>
