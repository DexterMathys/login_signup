<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Entity\User;

class SecurityController extends AppController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("password-recovery", name="password_recovery")
     */
    public function passwordRecoveryAction(Request $request, \Swift_Mailer $mailer)
    {
        /* POST data */
        if(!is_null($request->get('_recovery',null))){
            $recovery = trim($request->get('_recovery')); 
        }else{ 
            $recovery = '';
            if (is_null($request->get('_action',null))) {
                return $this->render('security/passwordRecovery.html.twig', array(
                    'last_username'     => $recovery,
                    'error'             => array('action'=>'','message'=>''),
                ));
            } else {
                return $this->render('security/passwordRecovery.html.twig', array(
                    'last_username'     => $recovery,
                    'error'             => array('action'=>'','message'=>utf8_encode('User cannot be empty.')),
                ));
            }
            
        }

        $recoveryRequestStatus = $this->recoveryRequest($recovery, $mailer);

        if($recoveryRequestStatus == '' || !isset($recoveryRequestStatus) || (is_array($recoveryRequestStatus) && $recoveryRequestStatus[0] === false)){
            if(is_array($recoveryRequestStatus) && $recoveryRequestStatus[0] === false){
                $message = $recoveryRequestStatus[1];
            } else
                $message = 'Invalid Email.';

            return $this->render('security/passwordRecovery.html.twig', array(
                // last username entered by the user
                'last_username' => $recovery,
                'error'         => array('status'=>1, 'action'=>'','message'=>$message, 'alert'=>'alert-danger'),
            ));
        }else{
            return $this->render('security/passwordRecovery.html.twig', array(
                'last_username'     => $recovery,
                'error'             => array('status'=>1, 'message'=>'The email contains a link that must be visited to complete the recovery.', 'alert' => 'alert-success'),
            ));
        }
    }

    /*
     * Verifies the request and sends recovery mail
     */
    public function recoveryRequest($recoveryUser, $mailer){

        $userRepository = $this->getDoctrine()->getRepository(User::class);

        $user = $userRepository->findOneByEmail($recoveryUser);

        if(!is_null($user)){
            if($user->getIsActive()){
                $email = $user->getEmail();

                if(strlen(trim($email)) <= 0)
                    return(array(false,'The User does not have an email.'));

                // Generate 10 character recovery code
                $recoveryCode = $this->rand_string(10);

                // Save the code generated in the database
                $user->setRecoveryCode($recoveryCode);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                // Send the email to complete the process
                $message = (new \Swift_Message('Recovery Password'))
                    ->setFrom('recovery@password.com')
                    ->setTo($email)
                    ->setBody(
                        $this->renderView(
                            'security/recovery_email.html.twig',
                            array('recovery_code' => $recoveryCode,
                                'recovery_user' => $recoveryUser)
                        ),
                        'text/html'
                    );
                $mailer->send($message);

                return(array(true));
            }else
                return(array(false,'The User is inactive.'));
        } else
            return(array(false,'Invalid Email.'));
    }

    /*
     * Generate a random string
     */
    public function rand_string( $length ) {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = '';
        $size = strlen( $chars );
        for( $i = 0; $i < $length; $i++ ) {
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }

        return $str;
    }

    /**
     * @Route("recovery/{code}", name="recovery")
     */
    public function recoveryAction($code)
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        $user = $userRepository->findOneByRecoveryCode($code);
        if(!is_null($user)){
            $email = $user->getEmail();
            //$username = $user->getUsername();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->render('security/passwordRecovery.html.twig', array(
                'code'=>$code,
                'error' => array('status'=>2, 'username'=>$email, 'message'=>''),
            ));
            
        }else{
            return $this->render('security/passwordRecovery.html.twig', array(
                'code'=>$code,
                'error' => array('status'=>0, 'action' => '', 'message'=>'Unexpected error when generating the password recovery, perform the process again.', 'alert' => 'alert-danger'),
            ));
        }
    }

    /**
     * @Route("new-password/{code}", name="new_password")
     */
    public function newPasswordAction($code, UserPasswordEncoderInterface $passwordEncoder)
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        $user = $userRepository->findOneByRecoveryCode($code);
        if(!is_null($user)){
            $email = $user->getEmail();
            //$username = $user->getUsername();

            if(!is_null($this->post->get('_password1',null))) $password1 = trim($this->post->get('_password1',null)); else $password1 = '';
            if(!is_null($this->post->get('_password2',null))) $password2 = trim($this->post->get('_password2',null)); else $password2 = '';

            if(strlen($password1) <= 0){
                return $this->render('security/passwordRecovery.html.twig', array(
                    'code'=>$code,
                    'error' => array(
                        'status'=>2,
                        'username'=>$email, 
                        'message'=>'The new password can not be empty.', 
                        'alert'=>'alert-danger'
                    ),
                ));
            }else if($password1 != $password2){
                return $this->render('security/passwordRecovery.html.twig', array(
                    'code'=>$code,
                    'error' => array(
                        'status'=>2,
                        'username'=>$email, 
                        'message'=>'The new password was not repeated correctly.', 
                        'alert'=>'alert-danger'
                    ),
                ));
            }

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $password1
                )
            );
            $user->setRecoveryCode(null);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->render('security/passwordRecovery.html.twig', array(
                'error' => array('status'=>3, 'username'=>$email, 'message'=>'The password was modified correctly, back to login.', 'alert' => 'alert-success'),
            ));

        }else{
            return $this->render('security/passwordRecovery.html.twig', array(
                'code'=>$code,
                'error' => array('status'=>2, 'message'=>'Unexpected error when generating the password, perform the process again.', 'alert' => 'alert-danger'),
            ));
        }
    }
}
