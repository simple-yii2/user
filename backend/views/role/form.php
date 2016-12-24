<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;
use yii\helpers\Html;

?>
<?php $form = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
	'options' => ['class' => 'role-form'],
]); ?>

	<?= Tabs::widget([
		'items' => [
			[
				'label' => Yii::t('users', 'General'),
				'content' => $this->render('form/general', ['form' => $form, 'model' => $model]),
				'active' => true,
			],
			[
				'label' => Yii::t('users', 'Security'),
				'content' => $this->render('form/child', ['form' => $form, 'model' => $model]),
			],
			[
				'label' => Yii::t('users', 'Users'),
				'content' => $this->render('form/assignment', ['model' => $model]),
			],
		],
	]) ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('users', 'Save'), ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('users', 'Cancel'), ['index'], ['class' => 'btn btn-link']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>
