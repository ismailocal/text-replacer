<?php

namespace IsmailOcal\TextReplacer;

class ContentReplacer
{
    private array $excludedDirectories = ['vendor', 'node_modules', '.git'];
    private ChangeLog $changeLog;
    
    public function __construct(
        private string $directory,
        private string $search,
        private string $replace
    ) {
        $this->changeLog = new ChangeLog();
    }

    /**
     * Set directories to exclude from content replacement
     */
    public function setExcludedDirectories(array $directories): void
    {
        $this->excludedDirectories = array_merge($this->excludedDirectories, $directories);
    }

    /**
     * Execute content replacement in files
     */
    public function replace(): ChangeLog
    {
        $this->replaceInDirectory($this->directory);
        return $this->changeLog;
    }

    /**
     * Replace content in files recursively
     */
    private function replaceInDirectory(string $directory): void
    {
        $files = array_diff(scandir($directory), ['.', '..']);

        foreach ($files as $file) {
            $path = $directory . '/' . $file;

            if (is_dir($path)) {
                if (!in_array($file, $this->excludedDirectories)) {
                    $this->replaceInDirectory($path);
                }
                continue;
            }

            $content = file_get_contents($path);
            $newContent = str_replace($this->search, $this->replace, $content);
            if ($content !== $newContent) {
                file_put_contents($path, $newContent);
                $this->changeLog->addModifiedContent($path);
            }
        }
    }
} 