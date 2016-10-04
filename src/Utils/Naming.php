<?php

/*
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz).
 */

namespace Symnedi\SymfonyBundlesExtension\Utils;

use Nette\Utils\Strings;

final class Naming
{
    public static function sanitazeClassName(string $name) : string
    {
        $name = Strings::webalize($name, '.');
        $name = strtr($name, ['-' => '_']);

        return $name;
    }
}
