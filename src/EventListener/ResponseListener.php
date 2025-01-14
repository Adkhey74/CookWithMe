<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ResponseListener
{
  public function onKernelResponse(ResponseEvent $event)
  {
    if (!$event->isMainRequest()) {
      return;
    }

    $response = $event->getResponse();
    $response->headers->set("Access-Control-Allow-Credentials", "true");
  }
}
