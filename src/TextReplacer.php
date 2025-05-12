<?php

namespace IsmailOcal\TextReplacer;

class TextReplacer
{
    private FileRenamer $fileRenamer;
    private ContentReplacer $contentReplacer;
    
    public function __construct(
        private string $directory,
        private string $search,
        private string $replace
    ) {
        $this->fileRenamer = new FileRenamer($directory, $search, $replace);
        $this->contentReplacer = new ContentReplacer($directory, $search, $replace);
    }

    /**
     * Set directories to exclude from text replacement
     */
    public function setExcludedDirectories(array $directories): void
    {
        $this->fileRenamer->setExcludedDirectories($directories);
        $this->contentReplacer->setExcludedDirectories($directories);
    }

    /**
     * Execute text replacement in directory and file contents
     */
    public function execute(): void
    {
        $this->fileRenamer->rename();
        $this->contentReplacer->replace();
    }
} 