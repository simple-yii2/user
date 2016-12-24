<?php

use yii\helpers\Html;

$title = Yii::t('user', 'Create user');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	['label' => Yii::t('user', 'Users'), 'url' => ['index']],
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?= $this->render('form', ['model' => $model]) ?>
