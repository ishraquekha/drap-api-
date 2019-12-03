<?php

	function clearParams()
	{
		if (isset($_GET) && count($_GET)>0)
		{
			foreach ($_GET AS $key => $val) $_GET[$key] = $GLOBALS['db']->qstr($val);
		}
		
		if (isset($_POST) && count($_POST)>0)
		{
			foreach ($_POST AS $key => $val)
			{
				if (!is_array($val)) $_POST[$key] = $GLOBALS['db']->qstr($val);
				else
				{
					$tmpArr = array();
					foreach ($val AS $keyArr => $valArr) $tmpArr[$keyArr] = $GLOBALS['db']->qstr($valArr);
					$_POST[$key] = $tmpArr;
				}
			}
		}
	}
	
	function c($val="")
	{
		return htmlentities($val);
	}
	
	function checkCsrf($post="")
	{
		$validacion = true;
		if ($post === "") $validacion = false;
		if ($post != $_SESSION['csrf']) $validacion = false;
		
		if (!$validacion)
		{
			LOG::agregar("ERROR_CSRF", "Clave Csrf no coincide", "PARAM: $post - SESSION: {$_SESSION['csrf']}");
			return false;
		}
		return true;
	}

?>