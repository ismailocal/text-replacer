<?php

namespace IsmailOcal\TextReplacer;

class ChangeLog
{
    private array $renamedFiles = [];
    private array $modifiedContents = [];

    public function addRenamedFile(string $oldPath, string $newPath): void
    {
        $this->renamedFiles[] = [
            'old' => $oldPath,
            'new' => $newPath
        ];
    }

    public function addModifiedContent(string $path): void
    {
        $this->modifiedContents[] = $path;
    }

    public function getRenamedFiles(): array
    {
        return $this->renamedFiles;
    }

    public function getModifiedContents(): array
    {
        return $this->modifiedContents;
    }

    public function hasChanges(): bool
    {
        return !empty($this->renamedFiles) || !empty($this->modifiedContents);
    }

    public function toString(): string
    {
        if (!$this->hasChanges()) {
            return "No changes were made.";
        }

        $output = [];

        if (!empty($this->renamedFiles)) {
            $output[] = "Renamed files:";
            foreach ($this->renamedFiles as $file) {
                $output[] = sprintf("  %s -> %s", $file['old'], $file['new']);
            }
        }

        if (!empty($this->modifiedContents)) {
            $output[] = "Modified files:";
            foreach ($this->modifiedContents as $file) {
                $output[] = sprintf("  %s", $file);
            }
        }

        return implode("\n", $output);
    }
} 