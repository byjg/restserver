<?php

namespace ByJG\RestServer;

class HttpRequest
{

    protected $get;
    protected $post;
    protected $server;
    protected $session;
    protected $cookie;
    protected $param;
    protected $phpRequest;

    /**
     *
     * @param array $get
     * @param array $post
     * @param array $server
     * @param array $session
     * @param array $cookie
     * @param array $param
     */
    public function __construct($get, $post, $server, $session, $cookie, $param = [])
    {
        $this->get = $get;
        $this->post = $post;
        $this->server = $server;
        $this->session = $session;
        $this->cookie = $cookie;
        $this->param = $param;

        $this->phpRequest = array_merge($get, $post, $server, $session, $cookie);
    }

    /**
     * Get a parameter passed by GET (the same as $_GET). If not found return false.
     *
     * @param string $value
     * @return string|boolean
     */
    public function get($value)
    {
        if (!isset($this->get[$value])) {
            return false;
        } else {
            return $this->get[$value];
        }
    }

    /**
     * Get a parameter passed by POST (the same as $_POST). If not found return false.
     *
     * @param string $value
     * @return string|boolean
     */
    public function post($value)
    {
        if (!isset($this->post[$value])) {
            return false;
        } else {
            return $this->post[$value];
        }
    }

    /**
     * Get the parameters sent by server (the same as $_SERVER). If not found return false.
     *
     * @param string $value
     * @return string|boolean
     */
    public function server($value)
    {
        if (!isset($this->server[$value])) {
            return false;
        } else {
            return $this->server[$value];
        }
    }

    /**
     * Get a server session value(the same as $_SESSION). If not found return false.
     *
     * @param string $value
     * @return string|boolean
     */
    public function session($value)
    {
        if (!isset($this->session[$value])) {
            return false;
        } else {
            return $this->session[$value];
        }
    }

    /**
     * Get the cookie sent by the client (the same as $_COOKIE). If not found return false.
     *
     * @param string $value
     * @return string|boolean
     */
    public function cookie($value)
    {
        if (!isset($this->cookie[$value])) {
            return false;
        } else {
            return $this->cookie[$value];
        }
    }

    /**
     * Get a value from any of get, post, server, cookie or session. If not found return false.
     *
     * @param string $value
     * @return string|boolean
     */
    public function request($value)
    {
        if (!isset($this->phpRequest[$value])) {
            return false;
        } else {
            return $this->phpRequest[$value];
        }
    }

    /**
     * Get a value from the params found in the URL
     *
     * @param string $value
     * @return string|boolean
     */
    public function param($value)
    {
        if (!isset($this->param[$value])) {
            return false;
        } else {
            return $this->param[$value];
        }
    }

    private $payload;

    /**
     * Get the payload passed during the request(the same as php://input). If not found return empty.
     *
     * @return string
     */
    public function payload()
    {
        if (is_null($this->payload)) {
            $this->payload = file_get_contents("php://input");
        }

        return $this->payload;
    }

    /**
     * Use this method to get the CLIENT REQUEST IP.
     * Note that if you behing a Proxy, the variable REMOTE_ADDR will always have the same IP
     * @return string
     */
    public function getRequestIp()
    {
        $headers = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];
        foreach ($headers as $header) {
            if ($this->server($header) !== false) {
                return $this->server($header);
            }
        }

        return 'UNKNOWN';
    }

    public function getServerName()
    {
        $headers = [
            'SERVER_NAME',
            'HTTP_HOST',
        ];
        foreach ($headers as $header) {
            if ($this->server($header) !== false) {
                return $this->server('SERVER_NAME');
            }
        }
        return $this->server('SERVER_ADDR');
    }

    /**
     * Use this method to get the SERVER NAME.
     * @param bool $port
     * @param bool $protocol
     * @return string
     */
    public function getRequestServer($port = false, $protocol = false)
    {
        $servername = $this->getServerName();

        if ($port && $this->server('SERVER_PORT' !== false)) {
            $servername .= ':' . $this->server('SERVER_PORT');
        }

        if ($protocol) {
            $servername = (
                ($this->server('HTTPS') !== 'off'
                    || $this->server('SERVER_PORT') == 443) ? "https://" : "http://") . $servername
            ;
        }

        return $servername;
    }

    private $uploadedFiles;

    /**
     * @return UploadedFiles
     */
    public function uploadedFiles()
    {
        if (!isset($this->uploadedFiles)) {
            $this->uploadedFiles = new UploadedFiles();
        }
        return $this->uploadedFiles;
    }

    public function appendVars($array)
    {
        $this->param = array_merge($this->param, $array);
    }
}
