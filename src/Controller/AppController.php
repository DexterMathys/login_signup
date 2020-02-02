<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AppController extends AbstractController {
    protected $post;
    protected $role;

    /**
     * @var integer HTTP status code - 200 (OK) by default
     */
    protected $statusCode = 200;
    
    public function __construct() {  
        $this->post = $this->obtainPostRequest();
    }

    /**
     * Convert to JSON and return a response with the header for content in JSON
     */
    protected function responseJSON($data) {
    	// Convert to JSON
    	$dataJSON = $this->serializeJson($data);
    	$response = new Response($dataJSON);
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }

    protected function serializeJson($data) {
        $encoders = array(new JsonEncoder());
		$normalizers = array(new ObjectNormalizer());
    	$serializer = new Serializer($normalizers, $encoders);
    	return $serializer->serialize($data, 'json');
    }

    /**
     * Obtain the $ _POST [] variable, analyzing the JSON 
     * in case it comes with the heading 'Content-type' = 'application / json'
     */
    protected function obtainPostRequest() {
        /*
         * If the request was sent in JSON format (for example from an mobile app) 
         * we will transform it into a format that can be interpreted by PHP
         */ 
        $request = Request::createFromGlobals();
        if ($request->headers->get('Content-Type') == 'application/json') {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : array());
        }
        return $request->request;
    }

    /**
     * Obtain the $_GET[] vaariable
     */
    protected function obtainGetRequest() {
        $request = Request::createFromGlobals();
        return $request->query;
    }

    /**
     * Obtain the complete request
     */
    protected function obtainRequest() {
        return Request::createFromGlobals();
    }

    /**
     * Verify that all the specified values are in the parameter collection.
     */
    protected function checkParameters(array $values, \Symfony\Component\HttpFoundation\ParameterBag $parameters) {
        foreach ($values as $value) {
            $valueToEvaluate = trim($parameters->get($value));
            if (strlen($valueToEvaluate) == 0) {
                return false;
            }
        }
        return true;
    }

    /**
     * Returns null if the value is not valid
     * @return string Value if valid, null otherwise
     */
    protected function obtainValue($value) {
        return (strlen($value) > 0) ? $value : null; // if it is empty
    }

    /**
     * Gets the value of statusCode.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the value of statusCode.
     *
     * @param integer $statusCode the status code
     *
     * @return self
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param array $headers
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Sets an error message and returns a JSON response
     *
     * @param string $errors
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondWithErrors($errors, $headers = [])
    {
        $data = [
            'errors' => $errors,
        ];

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Returns a 401 Unauthorized http response
     *
     * @param string $message
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondUnauthorized($message = 'Not authorized!')
    {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }

    /**
     * Returns a 422 Unprocessable Entity
     *
     * @param string $message
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondValidationError($message = 'Validation errors')
    {
        return $this->setStatusCode(422)->respondWithErrors($message);
    }

    /**
     * Returns a 404 Not Found
     *
     * @param string $message
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondNotFound($message = 'Not found!')
    {
        return $this->setStatusCode(404)->respondWithErrors($message);
    }

    /**
     * Returns a 201 Created
     *
     * @param array $data
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondCreated($data = [])
    {
        return $this->setStatusCode(201)->respond($data);
    }

    // this method allows us to accept JSON payloads in POST requests 
    // since Symfony 4 doesn't handle that automatically:

    protected function transformJsonBody(\Symfony\Component\HttpFoundation\Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }

    /* 
     * If it is empty then it returns null
     */
    protected function blankToNull($var){
        if ($var == '' || $var == 'null') {
            return null;
        }else{
            return $var;
        }
    }

    /**
     * Get the user role
     */
    protected function getRole()
    {
        $this->role = 'ROLE_USER';
        if ($this->isGranted('ROLE_ADMIN')) {
            $this->role = 'ROLE_ADMIN';
        }
        return $this->role;
    }

}