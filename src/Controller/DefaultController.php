<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{

    /**
     * The above function is a PHP controller method that renders the index.html.twig template for the
     * homepage route.     * 
     * @return The `indexAction` method is returning the rendered template `default/index.html.twig`.
     */
    #[Route('/', name: 'homepage')]
    public function indexAction()
    {
        return $this->render('default/index.html.twig');        
    }
    

}
