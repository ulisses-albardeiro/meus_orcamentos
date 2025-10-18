<?php

namespace sistema\Suporte;

use sistema\Controlador\UsuarioControlador;
use Twig\Lexer;
use sistema\Nucleo\Helpers;

class Template
{
    private \Twig\Environment $twig;

    public function __construct(string $diretorio = 'templates')
    {
        $loader = new \Twig\Loader\FilesystemLoader($diretorio);
        $this->twig = new \Twig\Environment($loader);

        $lexer = new Lexer($this->twig, array(
            $this->helpers()
        ));
        $this->twig->setLexer($lexer);
    }

    public function rendenizar(string $view, array $dados):string
    {
        return $this->twig->render($view, $dados);
    }

    private function helpers():void
    {
        array(
            //add metodos ao twig para poder usar nas views
            $this->twig->addFunction(
                new \Twig\TwigFunction('url', function(?string $url = null){
                    return Helpers::url($url);
                })
            ),
            $this->twig->addFunction(
                new \Twig\TwigFunction('textoResumido', function (string $text, int $limit, string $continues = '...') {
                    return Helpers::textoResumido($text, $limit, $continues);
                })
            ),

            $this->twig->addFunction(
                new \Twig\TwigFunction('flash', function(){
                    return Helpers::flash();
                })
            ),

            $this->twig->addFunction(
                new \Twig\TwigFunction('usuario', function(){
                    return UsuarioControlador::usuario();
                })
            ),
            $this->twig->addFunction(
                new \Twig\TwigFunction('empresa', function(){
                    return UsuarioControlador::empresa();
                })
            ),
            $this->twig->addFunction(
                new \Twig\TwigFunction('decodeHtml', function (string $texto) {
                    return Helpers::decodeHtml($texto);
                })
            ),
            $this->twig->addFunction(
                new \Twig\TwigFunction('contagemTempo', function($data){
                    return Helpers::contagemTempo($data);
                })
            )
            
        );
    }
}