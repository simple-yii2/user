<?php

use yii\helpers\Html;

$auth = \Yii::$app->authManager;

//roles
$roles = $auth->getRoles();
unset($roles['author']);
if ($model->item !== null) foreach ($roles as $k => $v) if (!$auth->canAddChild($model->item, $v)) unset($roles[$k]);
$roleItems = array_map(create_function('$v', 'return $v->name;'), $roles);

//permissions
$permissions = $auth->getPermissions();
unset($permissions['own']);
$permissionItems = array_map(create_function('$v', 'return $v->name;'), $permissions);

?>
<fieldset>
	<?php if (!empty($roleItems)) echo $form->field($model, 'roles')->checkboxList($roleItems, [
		'item' => function ($index, $label, $name, $checked, $value) use ($roles) {
			$description = $roles[$value]->description;
			if (!empty($description)) $label .= ' '.Html::tag('span', Html::encode('('.$description.')'), ['class' => 'text-muted']);
			return '<div class="checkbox">'.Html::checkbox($name, $checked, ['label' => $label, 'value' => $value]).'</div>';
		}
	]) ?>
	<?php if (!empty($permissionItems)) echo $form->field($model, 'permissions')->checkboxList($permissionItems, [
		'item' => function ($index, $label, $name, $checked, $value) use ($permissions) {
			$description = $permissions[$value]->description;
			if (!empty($description)) $label .= ' '.Html::tag('span', Html::encode('('.$description.')'), ['class' => 'text-muted']);
			return '<div class="checkbox">'.Html::checkbox($name, $checked, ['label' => $label, 'value' => $value]).'</div>';
		}
	]) ?>
</fieldset>
