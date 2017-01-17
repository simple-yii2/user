<?php

namespace cms\user\auth\clients;

class Facebook extends \yii\authclient\clients\Facebook
{
	
	public $attributeNames = ['email', 'first_name', 'last_name', 'picture'];

}
