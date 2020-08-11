<?php
    namespace App\Controller;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class indexController
    {
        public function index()
        {
            return new Response(
                '<html><body></body></html>'
            );
        }
    }
