<?php

use yii\helpers\Html;

$title = $model->getUsername();

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
    Yii::t('user', 'Security'),
    ['label' => Yii::t('user', 'Users'), 'url' => ['index']],
    $title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?= $this->render('form', ['model' => $model]) ?>
