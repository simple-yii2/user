<?php

use yii\helpers\Html;

$auth = \Yii::$app->authManager;

//roles
$roles = $auth->getRoles();
unset($roles['author']);
$roleItems = array_map(create_function('$v', 'return $v->name;'), $roles);

?>
<fieldset>
	<?php if (!empty($roleItems)) echo $form->field($model, 'roles')->checkboxList($roleItems, [
		'item'=>function ($index, $label, $name, $checked, $value) use ($roles) {
			$description = $roles[$value]->description;
			if (!empty($description)) $label .= ' '.Html::tag('span', Html::encode('('.$description.')'), ['class'=>'text-muted']);
			return '<div class="checkbox">'.Html::checkbox($name, $checked, ['label'=>$label, 'value'=>$value]).'</div>';
		}
	]) ?>
</fieldset>
