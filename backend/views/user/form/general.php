<fieldset>
	<?= $form->field($model, 'email')->textInput(['disabled'=>true]) ?>
	<?= $form->field($model, 'active')->checkbox() ?>
	<?= $form->field($model, 'comment')->textarea(['rows'=>3]) ?>
</fieldset>
