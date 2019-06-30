<?php

namespace Rest\Service;


use Zend\Stdlib\ResponseInterface;
use Zend\View\Model\JsonModel;

class Response
{

    private $result = true;
    private $messages = [];
    private $endpointAttributes = null;
    /**
     * @var ResponseInterface
     */
    public $response;


    /**
     * Response constructor.
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }


    /**
     * @return JsonModel
     */
    public function setResponse(): JsonModel
    {
        $endpointAttributes = $this->getEndpointAttributes();
        $attributes = is_null($endpointAttributes) ? json_decode('{}') : json_decode($endpointAttributes);

        return new JsonModel(
            [
                'result' => $this->getResult(),
                'messages' => $this->getMessages(),
                'api_content' => $attributes,
            ]
        );
    }

    /**
     * @param $httpStatusCode
     * @return Response
     */
    public function setHttpStatus($httpStatusCode)
    {
        $this->response->setStatusCode($httpStatusCode);

        return $this;
    }

    /**
     * @param mixed $result
     * @return Response
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @param mixed $message
     * @return Response
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * @return bool
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return array
     */
    public function getEndpointAttributes()
    {
        return $this->endpointAttributes;
    }

    /**
     * @param array $endpointAttributes
     */
    public function setEndpointAttributes($endpointAttributes)
    {

        $this->endpointAttributes = json_encode($endpointAttributes);
    }


}