<?php

namespace IsmailOcal\TextReplacer;

class FileRenamer
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
     * Set directories to exclude from file renaming
     */
    public function setExcludedDirectories(array $directories): void
    {
        $this->excludedDirectories = array_merge($this->excludedDirectories, $directories);
    }

    /**
     * Execute file renaming in directory
     */
    public function rename(): ChangeLog
    {
        $this->renameFilesInDirectory($this->directory);
        return $this->changeLog;
    }

    /**
     * Rename files in directory recursively
     */
    private function renameFilesInDirectory(string $directory): void
    {
        $files = array_diff(scandir($directory), ['.', '..']);

        foreach ($files as $file) {
            $path = $directory . '/' . $file;

            if (is_dir($path)) {
                if (!in_array($file, $this->excludedDirectories)) {
                    $this->renameFilesInDirectory($path);
                }
                continue;
            }

            $newPath = str_replace($this->search, $this->replace, $path);
            if ($path !== $newPath) {
                rename($path, $newPath);
                $this->changeLog->addRenamedFile($path, $newPath);
            }
        }
    }
} 