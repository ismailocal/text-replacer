<?php

namespace IsmailOcal\TextReplacer\Tests;

use IsmailOcal\TextReplacer\FileRenamer;
use PHPUnit\Framework\TestCase;

class FileRenamerTest extends TestCase
{
    private string $testDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testDir = sys_get_temp_dir() . '/file-renamer-test-' . uniqid();
        mkdir($this->testDir);
        
        // Create test directory structure
        mkdir($this->testDir . '/test_dir');
        file_put_contents($this->testDir . '/old_text_file.txt', 'test content');
        file_put_contents($this->testDir . '/test_dir/old_text_file.txt', 'test content');
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->testDir);
        parent::tearDown();
    }

    private function removeDirectory(string $dir): void
    {
        if (!file_exists($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->removeDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }

    public function testFileRenaming(): void
    {
        $renamer = new FileRenamer($this->testDir, 'old_text', 'new_text');
        $renamer->rename();

        // Check if file names have been changed
        $this->assertFileExists($this->testDir . '/new_text_file.txt');
        $this->assertFileExists($this->testDir . '/test_dir/new_text_file.txt');
        $this->assertFileDoesNotExist($this->testDir . '/old_text_file.txt');
        $this->assertFileDoesNotExist($this->testDir . '/test_dir/old_text_file.txt');
    }

    public function testExcludedDirectories(): void
    {
        // Create excluded test directory
        mkdir($this->testDir . '/vendor');
        file_put_contents($this->testDir . '/vendor/old_text_file.txt', 'test content');

        $renamer = new FileRenamer($this->testDir, 'old_text', 'new_text');
        $renamer->rename();

        // Check if file in excluded directory remains unchanged
        $this->assertFileExists($this->testDir . '/vendor/old_text_file.txt');
    }

    public function testCustomExcludedDirectories(): void
    {
        // Create custom excluded directory
        mkdir($this->testDir . '/custom_exclude');
        file_put_contents($this->testDir . '/custom_exclude/old_text_file.txt', 'test content');

        $renamer = new FileRenamer($this->testDir, 'old_text', 'new_text');
        $renamer->setExcludedDirectories(['custom_exclude']);
        $renamer->rename();

        // Check if file in custom excluded directory remains unchanged
        $this->assertFileExists($this->testDir . '/custom_exclude/old_text_file.txt');
    }
} 