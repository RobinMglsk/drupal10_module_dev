<?php

/**
 * @file
 * Hello world salutation event
 */

namespace Drupal\hello_world;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Event class to be dispatched from the HelloWorldSalutation service.
 */
class SalutationEvent extends Event
{
    const EVENT = 'hello_world.salutation_event';

    protected $message;

    /**
     * Retrieves the value of the message property.
     *
     * @return mixed The value of the message property.
     */
    public function getValue() {
        return $this->message;
    }

    /**
     * Sets the value of the message property.
     *
     * @param mixed $message The message to be assigned to the message property.
     * @return void
     */
    public function setValue($message): void {
        $this->message = $message;
    }
}
