<?php

/*
 * This file is part of Symplify.
 * Copyright (c) 2016 Tomas Votruba (http://tomasvotruba.cz).
 */

namespace Symplify\NetteAdapaterForSymfonyBundles\Utils;

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
