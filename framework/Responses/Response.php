<?php
namespace gfabrizi\PlainSimpleFramework\Responses;

use gfabrizi\PlainSimpleFramework\Config\Configurator;

class Response extends BaseResponse
{
    private string $view;
    private string $defaultLayout;
    private string $viewsUri;

    public function __construct(string $view, array $data = [], int $code = 200)
    {
        $configurator = Configurator::getInstance();
        $this->defaultLayout = $configurator->get('defaultLayout', 'BaseLayout');
        $this->viewsUri = $configurator->get('viewsUri', '/Views');

        $this->view = $view;
        $this->send($this->manageResponse($data), $code);
    }

    /**
     * Manages the response output
     *
     * @param array $data
     * @return string
     */
    private function manageResponse(array $data): string
    {
        $_mainLayout = $this->defaultLayout;
        extract($data, EXTR_SKIP);

        ob_start();
        require app_path($this->viewsUri . '/' . $this->view . '.php');
        $contentInLayout = ob_get_clean();

        ob_start();
        require app_path($this->viewsUri . '/' . $_mainLayout . '.php');

        return ob_get_clean();
    }
}