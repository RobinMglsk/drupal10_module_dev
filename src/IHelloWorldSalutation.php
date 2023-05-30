<?php

/**
 * @file
 * Hello world salutation service interface
 */

namespace Drupal\hello_world;


interface IHelloWorldSalutation
{
    public function getSalutation(): string;
}
