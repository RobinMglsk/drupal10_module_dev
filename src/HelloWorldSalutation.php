<?php

/**
 * @file
 * Hello world salutation service.
 */

namespace Drupal\hello_world;


use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\hello_world\IHelloWorldSalutation;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class HelloWorldSalutation implements IHelloWorldSalutation
{
    use StringTranslationTrait;

    protected ConfigFactoryInterface $configFactory;
    protected EventDispatcherInterface $eventDispatcher;

  
    public function __construct(ConfigFactoryInterface $config_factory, EventDispatcherInterface $eventDispatcher)
    {
        $this->configFactory = $config_factory;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Returns a salutation based on the time of day.
     *
     * @return string Returns a string with the salutation message.
     */
    public function getSalutation(): string
    {
        $config = $this->configFactory->get('hello_world.custom_salutation');
        $salutation = $config->get('salutation');
        if($salutation !== "" && $salutation) {
            $event = new SalutationEvent();
            $event->setValue($salutation);
            $event = $this->eventDispatcher->dispatch($event, SalutationEvent::EVENT);

            return $event->getValue();
        }


        $time = new DrupalDateTime();
        if ((int) $time->format('G') >= 00 && (int) $time->format('G') < 12) {
            return $this->t('Good morning world');
        }
        if ((int) $time->format('G') >= 12 && (int) $time->format('G') < 18) {
            return $this->t('Good afternoon world');
        }
        if ((int) $time->format('G') >= 18) {
            return $this->t('Good evening world');
        }
    }
}
