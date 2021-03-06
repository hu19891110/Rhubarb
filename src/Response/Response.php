<?php

/**
 * Copyright (c) 2016 RhubarbPHP.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Rhubarb\Crown\Response;

require_once __DIR__ . "/../Response/GeneratesResponseInterface.php";

use Rhubarb\Crown\Application;

class Response
{
    protected $headers;
    protected $content;
    protected $responseCode;
    protected $responseMessage;

    const HTTP_STATUS_SUCCESS_OK = 200;
    const HTTP_STATUS_REDIRECTION_PERMANENT = 301;
    const HTTP_STATUS_REDIRECTION_TEMPORARY = 302;
    const HTTP_STATUS_CLIENT_ERROR_BAD_REQUEST = 400;
    const HTTP_STATUS_CLIENT_ERROR_UNAUTHORIZED = 401;
    const HTTP_STATUS_CLIENT_ERROR_FORBIDDEN = 403;
    const HTTP_STATUS_CLIENT_ERROR_NOT_FOUND = 404;
    const HTTP_STATUS_CLIENT_ERROR_METHOD_NOT_ALLOWED = 405;
    const HTTP_STATUS_CLIENT_ERROR_CONFLICT = 409;
    const HTTP_STATUS_SERVER_ERROR_GENERIC = 500;

    /**
     * Records a reference to the object that generated this response.
     *
     * This is often used by unit tests to determine that url routing was successful, however it can also be useful
     * in complex rendering stacks where a response filter needs to know who generated the output to make an
     * appropriate response.
     *
     * @var GeneratesResponseInterface
     */
    protected $generator;

    public function __construct($generator = null)
    {
        $this->headers = ['Content-Type' => 'text/plain'];
        $this->content = null;
        $this->generator = $generator;
        $this->responseCode = self::HTTP_STATUS_SUCCESS_OK;
    }

    /**
     * Get's a reference to the object that generated this response.
     *
     * @return \Rhubarb\Crown\Response\GeneratesResponseInterface
     */
    public function getGenerator()
    {
        return $this->generator;
    }

    /**
     * Set's a header from a key value pair
     *
     * @param string $name Header name
     * @param string $value Header value
     */
    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * Unsets a header by it's name.
     *
     * @param string $name Name of header to unset
     */
    public function unsetHeader($name)
    {
        if (array_key_exists($name, $this->headers)) {
            unset($this->headers[$name]);
        }
    }

    /**
     * Gets a list of currently registered headers
     *
     * @return array Key-value header pairs
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Clears the array of headers to be sent.
     */
    public function clearHeaders()
    {
        $this->headers = [];
    }

    private function setHeaderInPhp($header)
    {
        if (!Application::current()->unitTesting){
            header($header);
        }
    }

    /*
     * Does the actual setting of headers for the response.
     */
    private function processHeaders()
    {
        if ($this->responseCode) {
            $this->setHeaderInPhp("HTTP/1.1 ".$this->getResponseCode()." ".$this->getResponseMessage());
        }

        foreach($this->headers as $type => $value){
            $this->setHeaderInPhp($type.": ".$value);
        }
    }

    /**
     * Sets the content of the response.
     *
     * @param mixed $content
     */
    final public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Returns the content of the response.
     *
     * We don't expect the content of a response to be fetched in this way except during unit testing.
     *
     * @return mixed
     */
    final public function getContent()
    {
        return $this->content;
    }

    /**
     * send the response by printing to the output buffer.
     */
    final public function send()
    {
        $this->processHeaders();
        $this->printContent();
    }

    protected function printContent()
    {
        print $this->formatContent();
    }

    /**
     * Returns the content of the response with any applicable formatting applied.
     *
     * This is the main function to override if extending the class.
     *
     * Note this is a public method as some Unit testing functions that simulate the request and response
     * model need to be able to access the response formatting.
     *
     * @return mixed The formatted content
     */
    public function formatContent()
    {
        return $this->getContent();
    }

    /**
     * Gets the current response code for the response
     * @return int
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * Sets the current response code for the response
     * @param int $responseCode
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
    }

    /**
     * Gets the current response code message for the response
     *
     * @return string
     */
    public function getResponseMessage()
    {
        return $this->responseMessage;
    }

    /**
     * Sets the current response code message for the response
     *
     * @param string $responseMessage
     */
    public function setResponseMessage($responseMessage)
    {
        $this->responseMessage = $responseMessage;
    }
}
