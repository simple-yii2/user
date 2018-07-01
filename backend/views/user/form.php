<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;

?>
<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableClientValidation' => false,
]); ?>

    <?= Tabs::widget([
        'items' => [
            [
                'label' => Yii::t('user', 'General'),
                'content' => $this->render('form/general', ['form' => $form, 'model' => $model]),
                'active' => true,
            ],
            [
                'label' => Yii::t('user', 'Security'),
                'content' => $this->render('form/assignment', ['form' => $form, 'model' => $model]),
            ],
        ],
    ]) ?>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('user', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
