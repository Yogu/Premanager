<?php

use Premanager\Execution\Environment;
use Premanager\IO\Request;

/**
 * Inserts a html POST formular with a hidden value for the current session key
 * 
 * Parameters:
 *   - action: the url for the POST request. Ommit to use the request url
 *   - multipart: set to true if the form contains a <input type="file"> element
 *     (sets the attribute enctype="multipart/form-data")
 * 
 * Example:
 * {form action="./members/login"}
 *   <input type="text" name="name" />
 *   <input type="password" name="password" />
 * {/form}
 * 
 * Outputs:
 * <form method="post" action="./members/login">
 *   <input type="hidden" name="postValidator" value="0123456789abcdef" />
 *   <input type="text" name="name" />
 *   <input type="password" name="password" />
 * </form>
 */
class Dwoo_Plugin_form extends Dwoo_Block_Plugin implements Dwoo_ICompilable_Block {
	public function init($action = '', $multipart = false)
	{
	}

	public static function preProcessing(Dwoo_Compiler $compiler, array $params, $prepend, $append, $type)
	{
		return '';
	}

	public static function postProcessing(Dwoo_Compiler $compiler, array $params, $prepend, $append, $content)
	{
		$rparams = $compiler->getRealParams($params);
		$cparams = $compiler->getCompiledParams($params);
		
		$action = $cparams['action'];
		$multipart = $cparams['multipart'];
		
		$pre = '<form method="post" action="'.Dwoo_Compiler::PHP_OPEN.
			'echo htmlspecialchars('.$action.' ? '.$action.' : '.
			'Premanager\IO\Request::getRequestURL()).\'"\';'.
			'if ('.$cparams['multipart'].') echo \' enctype="multipart/form-data"\';'.
			'echo \'>\';'.
			'if (Premanager\Execution\Environment::getCurrent()->getSession()) '.
			'echo \'<input type="hidden" name="postValidator" '.
			'value="\'.htmlspecialchars('.
			'Premanager\Execution\Environment::getCurrent()->getSession()->getKey()).\'" />\';'.
			Dwoo_Compiler::PHP_CLOSE;
				
		$post = '</form>';

		return $pre . $content . $post;
	}
}
