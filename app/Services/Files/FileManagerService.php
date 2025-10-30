<?php

namespace App\Services\Files;

use RuntimeException;

class FileManagerService implements FileManagerInterface
{
    /**
     * Force download of a file.
     */
    public function download(string $content, string $filename, string $contentType): void
    {
        $filename = $this->sanitizeFilename($filename);
        $this->sendHeaders($contentType, $filename, 'attachment');
        echo $content;
    }

    /**
     * Display file inline in the browser.
     */
    public function display(string $content, string $filename, string $contentType): void
    {
        $filename = $this->sanitizeFilename($filename);
        $this->sendHeaders($contentType, $filename, 'inline');
        echo $content;
    }

    /**
     * Save content to a file.
     */
    public function save(string $content, string $path, string $filename): void
    {
        $filename = $this->sanitizeFilename($filename);

        if (!is_dir($path) && !mkdir($path, 0755, true) && !is_dir($path)) {
            throw new RuntimeException("Could not create directory: {$path}");
        }

        $fullPath = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

        if (file_put_contents($fullPath, $content) === false) {
            throw new RuntimeException("Could not save the file: {$fullPath}");
        }
    }

    /**
     * Delete a file.
     */
    public function delete(string $path): void
    {
        if (!file_exists($path)) {
            return; // already deleted
        }

        if (!unlink($path)) {
            throw new RuntimeException("Failed to delete file: {$path}");
        }
    }

    /**
     * Send proper headers for download or display.
     */
    private function sendHeaders(string $contentType, string $filename, string $disposition = 'attachment'): void
    {
        header("Content-Type: $contentType");
        header("Content-Disposition: {$disposition}; filename=\"{$filename}\"");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Expires: 0');
    }

    /**
     * Sanitize filename to prevent directory traversal and invalid characters.
     */
    private function sanitizeFilename(string $filename): string
    {
        return basename($filename);
    }
}
