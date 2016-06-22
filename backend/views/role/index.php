<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$title = Yii::t('user', 'Roles');

$this->title = $title . '|' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<div class="btn-toolbar" role="toolbar">
	<?= Html::a(Yii::t('user', 'Create'), ['create'], ['class' => 'btn btn-primary']) ?>
</div>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'summary' => '',
	'showHeader' => false,
	'tableOptions' => ['class' => 'table table-condensed'],
	'columns' => [
		[
			'header' => Yii::t('user', 'Name'),
			'format' => 'html',
			'value' => function($model, $key, $index, $column) {
				$r = Html::encode($model->name);
				if (!empty($model->description)) $r .= ' '.Html::tag('span', Html::encode('('.$model->description.')'), ['class' => 'text-muted']);
				$children = \Yii::$app->authManager->getChildren($model->name);
				foreach ($children as $child) {
					$r .= ' '.Html::tag('span', Html::encode($child->name), [
						'class' => $child->type == $child::TYPE_ROLE ? 'label label-primary' : 'label label-default',
						'title' => $child->description,
					]);
				}
				return $r;
			},
		],
		[
			'class' => 'yii\grid\ActionColumn',
			'options' => ['style' => 'width: 50px;'],
			'template' => '{update} {delete}',
			'urlCreator' => function($action, $model, $key, $index) {
				return Url::toRoute([$action, 'name' => $model->name]);
			},
		],
	],
]) ?>
