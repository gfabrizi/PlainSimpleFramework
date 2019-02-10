<?php
namespace gfabrizi\PlainSimpleFramework\Http;

use gfabrizi\PlainSimpleFramework\Config\Configurator;

class Router
{
    private $request;
    private $namespace;
    private $get = [];
    private $post = [];
    private $put = [];
    private $delete = [];

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
        $this->namespace = Configurator::getInstance()->get('controllersNamespace', 'App\Controllers');
    }

    public function get($route, $method)
    {
        $this->get[$this->formatRoute($route)] = $method;
    }

    public function post($route, $method)
    {
        $this->post[$this->formatRoute($route)] = $method;
    }

    public function put($route, $method)
    {
        $this->put[$this->formatRoute($route)] = $method;
    }

    public function delete($route, $method)
    {
        $this->delete[$this->formatRoute($route)] = $method;
    }

    /**
     * Removes slashes from start and end of the route, removes query string and manages url placeholder
     *
     * @param $route (string)
     * @return string
     */
    private function formatRoute($route)
    {
        $result = rtrim($route, '/');
        if ($result === '')
        {
            return '/';
        }

        if(false !== strpos($route, '?')) {
            $arr = explode('?', $route, 2);
            $result = $arr[0];
        }

        $result = preg_replace('/(\/\{.*\}\/?)$/', '/{param}', $result);

        return $result;
    }

    private function defaultRequestHandler()
    {
        header("{$this->request->get('serverProtocol')} 404 Not Found");
    }

    /**
     * Resolves the route and manages the request, forwarding it to designed callback or controller
     */
    function resolve()
    {
        $callback = null;
        $methodDictionary = $this->{strtolower($this->request->get('requestMethod'))};
        $formattedRoute = $this->formatRoute($this->request->get('requestUri'));
        $param = [$this->request];

        if (isset($methodDictionary[$formattedRoute])) {
            $callback = $methodDictionary[$formattedRoute];
        } else {
            preg_match('/(\/.*)\/(.*)$/', $formattedRoute, $matches);
            if ((count($matches) == 3) && isset($methodDictionary[$matches[1] . '/{param}'])) {
                $callback = $methodDictionary[$matches[1] . '/{param}'];
                $param[] = $matches[2];
            }
        }

        if (null === $callback)
        {
            $this->defaultRequestHandler();
            return;
        }

        if (is_callable($callback)) {
            echo call_user_func_array($callback, $param);
        } else {
            list($class, $method) = explode('@', $callback);
            $classname = $this->namespace . '\\' . $class;
            if (class_exists($classname) && method_exists($classname, $method)) {
                $controller = new $classname;
                call_user_func_array(array($controller, $method), $param);
            } else {
                $this->defaultRequestHandler();
                return;
            }
        }
    }

    function __destruct()
    {
        $this->resolve();
    }
}