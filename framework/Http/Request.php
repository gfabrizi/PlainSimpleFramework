<?php
namespace gfabrizi\PlainSimpleFramework\Http;

class Request implements RequestInterface
{
    public function __construct()
    {
        $this->bootstrapSelf();
    }

    private function bootstrapSelf(): void
    {
        foreach ($_SERVER as $key => $value)
        {
            $this->{$this->toCamelCase($key)} = $value;
        }
    }

    private function toCamelCase($string)
    {
        $result = strtolower($string);

        preg_match_all('/_[a-z]/', $result, $matches);
        foreach ($matches[0] as $match)
        {
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }
        return $result;
    }

    /**
     * Set custom headers from this request as an array $key => $value
     *
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        foreach ($headers as $key => $value)
        {
            $this->{$this->toCamelCase($key)} = $value;
        }
    }

    public function getBody(): array
    {
        $result = array();

        if ($this->get('requestMethod') === 'GET') {
            foreach ($_GET as $key => $value)
            {
                $result[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        } else if ($this->get('requestMethod') === 'POST') {
            foreach ($_POST as $key => $value)
            {
                $result[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        } else if ($this->get('requestMethod') === 'PUT') {
            $result[] = file_get_contents('php://input');
        }

        return $result;
    }

    public function get($property)
    {
        return $this->{$property} ?? null;
    }
}