<?php

use yii\helpers\Html;

$title = $model->item->name;

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
    Yii::t('user', 'Security'),
    ['label' => Yii::t('user', 'Permissions'), 'url' => ['index']],
    $title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?= $this->render('form', ['model' => $model]) ?>
