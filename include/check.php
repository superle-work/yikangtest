<?php
/**
 *change team
 * ============================================================================
 * * COPYRIGHT change 2015.
 * http://www.changekeji.com;
 * ----------------------------------------------------------------------------
 */

class Check
{
	/**
	 * 判断用户名是否规范
	 */
	function isUsername($username)
	{
		if (preg_match("/^[a-zA-Z]{1}([0-9a-zA-Z]|[._]){3,19}$/", $username))
		{
			return true;
		}
	}

	/**
	 * 判断密码是否规范
	 */
	function isPassword($password)
	{
		if (preg_match("/^[\@A-Za-z0-9\!\#\$\%\^\&\*\.\~]{6,22}$/", $password))
		{
			return true;
		}
	}

	/**
	 * 判断验证码是否规范
	 */
	function isCaptcha($captcha)
	{
		if (preg_match("/^[A-Za-z0-9]{4}$/", $captcha))
		{
			return true;
		}
	}

	/**
	 * 判断别名是否规范
	 */
	function isUniqueId($unique)
	{
		if (preg_match("/^[a-z0-9-]+$/", $unique))
		{
			return true;
		}
	}

	/**
	 * 判断价格是否规范
	 */
	function isPrice($price)
	{
		if (preg_match("/^[0-9.]+$/", $price))
		{
			return true;
		}
	}

	/**
	 * 判断备份文件名是否规范
	 */
	function isBackupFile($file_name)
	{
		if (preg_match("/^[a-zA-Z0-9]+$/", $file_name))
		{
			return true;
		}
	}

	/**
	 +----------------------------------------------------------
	 * 判断目录是否可写
	 +----------------------------------------------------------
	 */
	function isWrite($dir)
	{
		//判断目录是否存在
		if (file_exists($dir))
		{ 
		  //判断目录是否可写
			if ($fp = @fopen("$dir/test.txt", 'w'))
			{
				@fclose($fp);
				@unlink("$dir/test.txt");
				$writeable = 1;
			}
			else
			{
				$writeable = 0;
			}
		}
		else
		{
			$writeable = 2;
		}
	
		return $writeable;
	}




}
?>