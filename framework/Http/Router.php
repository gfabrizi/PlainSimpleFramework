<?php
namespace gfabrizi\PlainSimpleFramework\Http;

use gfabrizi\PlainSimpleFramework\Config\Configurator;

final class Router
{
    private string $namespace;

    private array $get = [];
    private array $post = [];
    private array $put = [];
    private array $delete = [];

    public function __construct(private RequestInterface $request)
    {
        $this->namespace = Configurator::getInstance()->get('controllersNamespace', 'App\Controllers');
    }

    public function get($route, $method): void
    {
        $this->get[$this->formatRoute($route)] = $method;
    }

    public function post($route, $method): void
    {
        $this->post[$this->formatRoute($route)] = $method;
    }

    public function put($route, $method): void
    {
        $this->put[$this->formatRoute($route)] = $method;
    }

    public function delete($route, $method): void
    {
        $this->delete[$this->formatRoute($route)] = $method;
    }

    /**
     * Removes slashes from start and end of the route, removes query string and manages url placeholder
     *
     * @param $route (string)
     * @return string
     */
    private function formatRoute($route): string
    {
        $result = rtrim($route, '/');
        if ($result === '')
        {
            return '/';
        }

        if (str_contains($route, '?')) {
            $arr = explode('?', $route, 2);
            $result = $arr[0];
        }

        return preg_replace('/(\/{.*}\/?)$/', '/{param}', $result);
    }

    private function defaultRequestHandler(): void
    {
        if (true === headers_sent()) {
            http_response_code(404);
        } else {
            header("{$this->request->get('serverProtocol')} 404 Not Found");
        }
    }

    /**
     * Resolves the route and manages the request, forwarding it to designed callback or controller
     */
    protected function resolve(): void
    {
        $callback = null;
        $methodDictionary = $this->{strtolower($this->request->get('requestMethod'))};
        $formattedRoute = $this->formatRoute($this->request->get('requestUri'));
        $param = [$this->request];

        if (isset($methodDictionary[$formattedRoute])) {
            $callback = $methodDictionary[$formattedRoute];
        } else {
            preg_match('/(\/.*)\/(.*)$/', $formattedRoute, $matches);
            if (count($matches) > 0 ) {
                $matchAndParam = $matches[1] . '/{param}';
                if (isset($methodDictionary[$matchAndParam]) && (count($matches) === 3)) {
                    $callback = $methodDictionary[$matchAndParam];
                    $param[] = $matches[2];
                }
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
            [$class, $method] = explode('@', $callback);
            $classname = $this->namespace . '\\' . $class;
            if (class_exists($classname) && method_exists($classname, $method)) {
                $controller = new $classname;
                call_user_func_array(array($controller, $method), $param);
            } else {
                $this->defaultRequestHandler();
            }
        }
    }

    public function __destruct()
    {
        $this->resolve();
    }
}