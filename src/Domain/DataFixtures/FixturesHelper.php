<?php

namespace App\Domain\DataFixtures;

use Symfony\Component\Yaml\Yaml;

class FixturesHelper
{
    public function get(string $entityName)
    {
        $fixturesPath = __DIR__.'/Fixtures/';
        $fixtures = Yaml::parse(file_get_contents($fixturesPath.$entityName.'.yaml', true));

        return $fixtures;
    }

    public function getCallable(string $referenceEntity, $Instance)
    {
        $callableSetter = 'set'.ucfirst($referenceEntity);
        $callableAdder = 'add'.ucfirst($referenceEntity);

        if (method_exists($Instance, $callableSetter)) {
            return $callableSetter;
        } elseif (method_exists($Instance, $callableAdder)) {
            return $callableAdder;
        } else {
            throw new \Exception('INVALID FIXTURE REFERENCE KEY OR VALUE : Doesn\'t match with the method in class '.__CLASS__);
        }
    }
}
