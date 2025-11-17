<?php

namespace App\Adapters;

use App\Controllers\TemplateController;
use Twig\Lexer;
use App\Core\Helpers;

class Template
{
    private \Twig\Environment $twig;

    public function __construct(string $directory = 'templates')
    {
        $loader = new \Twig\Loader\FilesystemLoader($directory);
        $this->twig = new \Twig\Environment($loader);

        $lexer = new Lexer($this->twig, array(
            $this->helpers()
        ));
        $this->twig->setLexer($lexer);
    }

    public function render(string $view, array $data):string
    {
        return $this->twig->render($view, $data);
    }

    private function helpers():void
    {
        array(
            $this->twig->addFunction(
                new \Twig\TwigFunction('url', function(?string $url = null){
                    return Helpers::url($url);
                })
            ),
            $this->twig->addFunction(
                new \Twig\TwigFunction('truncateText', function (string $text, int $limit, string $continues = '...') {
                    return Helpers::truncateText($text, $limit, $continues);
                })
            ),

            $this->twig->addFunction(
                new \Twig\TwigFunction('flash', function(){
                    return Helpers::flash();
                })
            ),

            $this->twig->addFunction(
                new \Twig\TwigFunction('user', function(){
                    return TemplateController::user();
                })
            ),
            $this->twig->addFunction(
                new \Twig\TwigFunction('company', function(){
                    return TemplateController::company();
                })
            ),
            $this->twig->addFunction(
                new \Twig\TwigFunction('countTime', function($data){
                    return Helpers::countTime($data);
                })
            )
            
        );
    }
}