<?php
namespace App\Controller;

// Librerias para controlador
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller; // Controlador base de Symfony
use Symfony\Component\HttpFoundation\Response;

/**
 * Welcome
 */
class WelcomeController extends Controller {

    public function __construct() {
        
    }

    /**
      * @Route("/", name="index")
      */
    public function indexAction() {
      $user = $this->getUser();
      if (!$user) {
          return $this->redirectToRoute('app_login');
      }
      return $this->render('Welcome/index.html.twig', array('user' => $user));
    }

}