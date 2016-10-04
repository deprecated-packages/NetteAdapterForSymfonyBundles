<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\TacticianBundle\NetteTagsSource;

class SomeCommandHandler
{
    public function handle(SomeCommand $someCommand)
    {
        $someCommand->setState('changedState');
    }
}
