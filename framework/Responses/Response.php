<?php
namespace gfabrizi\PlainSimpleFramework\Responses;

use gfabrizi\PlainSimpleFramework\Config\Configurator;

class Response implements ResponseInterface
{
    private $view;
    private $defaultLayout;
    private $viewsUri;

    public function __construct(string $view, array $data = [], int $code = 200)
    {
        $this->defaultLayout = Configurator::getInstance()->get('defaultLayout', 'BaseLayout');
        $this->viewsUri = Configurator::getInstance()->get('viewsUri', '/Views');

        $this->view = $view;
        http_response_code($code);
        $output = $this->manageResponse($data);
        echo $output;
    }

    /**
     * Manages the response output
     *
     * @param $data
     * @return false|string
     */
    private function manageResponse(array $data): string
    {
        extract($data);

        ob_start();
        require app_path() . $this->viewsUri . '/' . $this->view . '.php';
        $contentInLayout = ob_get_clean();

        $_mainLayout = isset($_mainLayout) ? $_mainLayout : $this->defaultLayout;
        ob_start();
        require app_path(). $this->viewsUri . '/' . $_mainLayout . '.php';
        $output = ob_get_clean();

        return $output;
    }
}