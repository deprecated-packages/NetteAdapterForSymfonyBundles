<?php

namespace Symplify\SymfonyBundlesExtension\Tests\TacticianBundle\NetteTagsSource;

class SomeCommandHandler
{
    public function handle(SomeCommand $someCommand)
    {
        $someCommand->setState('changedState');
    }
}
