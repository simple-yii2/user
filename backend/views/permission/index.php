<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$title = Yii::t('user', 'Permissions');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
    Yii::t('user', 'Security'),
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
    'columns' => [
        [
            'header' => Yii::t('user', 'Name'),
            'format' => 'html',
            'value' => function($model, $key, $index, $column) {
                $r = Html::encode($model->name);
                if (!empty($model->description))
                    $r .= ' '.Html::tag('span', Html::encode('('.$model->description.')'), ['class' => 'text-muted']);

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
