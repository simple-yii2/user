<?php

$emailOptions = [];
if ($model->scenario !== 'create')
    $emailOptions['disabled'] = true;

?>
<fieldset>
    <?= $form->field($model, 'admin')->checkbox() ?>
    <?= $form->field($model, 'active')->checkbox() ?>
    <?= $form->field($model, 'email')->textInput($emailOptions) ?>
    <?= $form->field($model, 'firstName') ?>
    <?= $form->field($model, 'lastName') ?>
    <?= $form->field($model, 'comment')->textarea(['rows' => 3]) ?>
</fieldset>
