<?php

class ErrException extends Exception
{
	public function errorInfo()
	{
		$err = $this->getMessage();
		return $err;
	}
}
