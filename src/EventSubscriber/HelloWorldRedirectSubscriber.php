<?php

/**
 * @file
 * Hello world redirect subscriber
 */

namespace Drupal\hello_world\EventSubscriber;

use \Drupal\Core\Routing\LocalRedirectResponse;
use \Drupal\Core\Routing\RouteMatchInterface;
use \Drupal\Core\Session\AccountProxyInterface;
use \Drupal\Core\Url;
use \Symfony\Component\EventDispatcher\EventSubscriberInterface;
use \Symfony\Component\HttpKernel\Event\RequestEvent;
use \Symfony\Component\HttpKernel\KernelEvents;

/**
 * Redirect to the homepage when the user has the "non_grata" role.
 */
class HelloWorldRedirectSubscriber implements EventSubscriberInterface
{

    /**
     * @var \Drupal\Core\Session\AccountProxyInterface
     */
    protected AccountProxyInterface $currentUser;

    /**
     * @var \Drupal\Core\Routing\RouteMatchInterface
     */
    protected RouteMatchInterface $routeMatch;

    /**
     * Constructs a new instance of the class.
     *
     * @param \Drupal\Core\Session\Account\AccountProxyInterface $currentUser the current user account proxy
     */
    public function __construct(AccountProxyInterface $currentUser, RouteMatchInterface $routeMatch)
    {
        $this->currentUser = $currentUser;
        $this->routeMatch = $routeMatch;
    }

    public static function getSubscribedEvents(): array
    {
        $events[KernelEvents::REQUEST][] = ['onRequest', 0];
        return $events;
    }

    public function onRequest(RequestEvent $event)
    {
        // $request = $event->getRequest();
        // $path = $request->getPathInfo();
        // if($path !== '/hello'){
        //     return;
        // }

        $route_name = $this->routeMatch->getRouteName();
        if($route_name !== 'hello_world.hello'){
            return;
        }

        $roles = $this->currentUser->getRoles();
        if(in_array('non_grata', $roles)){
            $url = Url::fromUri('internal:/');
            $event->setResponse(
                new LocalRedirectResponse($url->toString())
            );
        }
    }
}
