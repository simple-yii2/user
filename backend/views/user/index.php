<?php

use yii\grid\GridView;
use yii\helpers\Html;

$title = Yii::t('users', 'Users');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<div class="btn-toolbar" role="toolbar">
	<?= Html::a(Yii::t('users', 'Create'), ['create'], ['class' => 'btn btn-primary']) ?>
</div>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $model,
	'summary' => '',
	'tableOptions' => ['class' => 'table table-condensed'],
	'rowOptions' => function ($model, $key, $index, $grid) {
		return !$model->active ? ['class' => 'warning'] : [];
	},
	'columns' => [
		[
			'attribute' => 'email',
			'format' => 'html',
			'value' => function($model, $key, $index, $column) {
				$r = Html::encode($model->email);

				$s = $model->username;
				if ($s !== $model->email)
					$r .= ' ' . Html::tag('span', Html::encode('(' . $s . ')'), ['class' => 'text-muted']);

				if ($model->admin)
					$r .= ' ' . Html::tag('span', 'A' , [
						'class' => 'label label-danger',
						'title' => $model->getAttributeLabel('admin'),
					]);

				foreach (Yii::$app->authManager->getRolesByUser($model->id) as $role) if ($role->name !== 'author') {
					$r .= ' ' . Html::tag('span', Html::encode($role->name), [
						'class' => 'label label-primary',
						'title' => $role->description,
					]);
				}

				return $r;
			},
		],
		[
			'class' => 'yii\grid\ActionColumn',
			'options' => ['style' => 'width: 50px;'],
			'template' => '{update} {password}',
			'buttons' => [
				'password' => function ($url, $model, $key) {
					$title = Yii::t('users', 'Set password');

					return Html::a('<span class="glyphicon glyphicon-lock"><span>', ['password', 'id' => $model->id], [
						'title' => $title,
						'aria-label' => $title,
						'data-pjax' => 0,
					]);
				},
			],
		],
	],
]) ?>
