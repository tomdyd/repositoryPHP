<?php

namespace App;

class App
{
    /**
     * @var string
     */
    private string $page;

    /**
     * @var Layout
     */
    private Layout $layout;

    /**
     * Lista stron dozwolonych
     *
     * @var array|string[]
     */
    private array $allowedPages = [
        'shop',
        'process_order',
        'generate'
    ];

    public function getPage(): string
    {
        return $this->page;
    }

    /**
     * Pierwsza funkcja ładowana podczas odpalenia aplikacji
     * Ładujemy routing
     * Ładujemy layout
     *
     * @return void
     */
    public function run(): void
    {
        $this->parserRouting();
        $this->layout = new Layout($this->page, 'default');
        $this->layout->render();
    }

    /**
     * Pobiera to co wpisał użytkownik w metodzie GET
     *
     * @return void
     */
    private function parserRouting(): void
    {
        $page = $_GET['page'] ?? 'shop';
        $this->page = in_array($page, $this->allowedPages) ? $page : 'shop';
    }
}