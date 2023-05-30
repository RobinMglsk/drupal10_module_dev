<?php

/**
 * @file
 * Hello world controller
 */

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\hello_world\IHelloWorldSalutation;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Hello world controller
 */
class HelloWorldController extends ControllerBase {

    protected IHelloWorldSalutation $salutation;

    public function __construct(IHelloWorldSalutation $salutation) {
        $this->salutation = $salutation;
    }
    
    /**
     * Returns an array with a single key '#markup' and the value of the translation of 'Hello world!'.
     *
     * @return array{string:string}
     */
    public function helloWorld(): array {
        return [
            '#markup' => $this->salutation->getSalutation(),
        ];
    }

    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('hello_world.salutation')
        );
    }
}