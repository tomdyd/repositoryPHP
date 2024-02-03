<?php

namespace App;

class Layout
{
    private string $page;

    private string $layoutName;

    private string $title = 'Sklep z procesorami';

    public function __construct(
        string $page,
        string $layoutName
    ) {
        $this->page = $page;
        $this->layoutName = $layoutName;
    }

    public function render()
    {
        extract([
                'title' => $this->title,
                'content' => $this->renderPage()
            ]
        );
        include __DIR__ . "/layouts/{$this->layoutName}.php";
    }

    private function renderPage()
    {
        ob_start();
        include __DIR__ . "/page/{$this->page}.php";
        return ob_get_clean();
    }

}