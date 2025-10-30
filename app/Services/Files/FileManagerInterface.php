<?php

namespace App\Services\Files;

interface FileManagerInterface
{
    public function download(string $content, string $filename, string $contentType): void;
    public function save(string $content, string $path, string $filename): void;
    public function display(string $content, string $filename, string $contentType): void;
    public function delete(string $path): void;
}