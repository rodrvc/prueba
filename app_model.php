<?php
App::import('Lib', 'LazyModel.LazyModel');

class AppModel extends LazyModel
{
	//var $useDbConfig	= 'desarrollo';
	var $actsAs			= array('Containable');
	var $recursive		= -1;
}
