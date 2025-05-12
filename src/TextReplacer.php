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
    public function execute(): ChangeLog
    {
        $fileChanges = $this->fileRenamer->rename();
        $contentChanges = $this->contentReplacer->replace();

        $changeLog = new ChangeLog();
        foreach ($fileChanges->getRenamedFiles() as $file) {
            $changeLog->addRenamedFile($file['old'], $file['new']);
        }
        foreach ($contentChanges->getModifiedContents() as $file) {
            $changeLog->addModifiedContent($file);
        }

        return $changeLog;
    }
} 