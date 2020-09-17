<?php
    namespace App\Controller;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class indexController extends AbstractController
    {
        public function index()
        {
            $words = ['sky', 'cloud', 'wood', 'rock', 'forest',
                'mountain', 'breeze'];

            return $this->render('base.html.twig', [
                'words' => $words
            ]);
        }
    }
