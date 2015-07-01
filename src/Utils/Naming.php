<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\SymfonyBundlesExtension\Utils;

use Nette\Utils\Strings;


class Naming
{

	/**
	 * @param string $name
	 * @return string
	 */
	public static function sanitazeClassName($name)
	{
		$name = Strings::webalize($name, '.');
		$name = strtr($name, ['-' => '_']);
		return $name;
	}

}
