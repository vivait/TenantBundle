<?php

namespace test\Vivait\TenantBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class DemoController {
    public function indexAction() {
        return new Response('Hello World');
    }

    public function tenant1Action() {
        return new Response('Hello Tenant 1');
    }

    public function tenant2Action() {
        return new Response('Hello Tenant 2');
    }
}
