<?php

namespace IsmailOcal\TextReplacer\Tests;

use IsmailOcal\TextReplacer\ContentReplacer;
use PHPUnit\Framework\TestCase;

class ContentReplacerTest extends TestCase
{
    private string $testDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testDir = sys_get_temp_dir() . '/content-replacer-test-' . uniqid();
        mkdir($this->testDir);
        
        // Create test directory structure
        mkdir($this->testDir . '/test_dir');
        file_put_contents($this->testDir . '/test_file.txt', 'a file containing old_text');
        file_put_contents($this->testDir . '/test_dir/test_file.txt', 'a file containing old_text');
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

    public function testContentReplacement(): void
    {
        $replacer = new ContentReplacer($this->testDir, 'old_text', 'new_text');
        $replacer->replace();

        // Check if file contents have been changed
        $this->assertStringContainsString(
            'new_text',
            file_get_contents($this->testDir . '/test_file.txt')
        );
        $this->assertStringContainsString(
            'new_text',
            file_get_contents($this->testDir . '/test_dir/test_file.txt')
        );
    }

    public function testExcludedDirectories(): void
    {
        // Create excluded test directory
        mkdir($this->testDir . '/vendor');
        file_put_contents(
            $this->testDir . '/vendor/test_file.txt',
            'a file containing old_text'
        );

        $replacer = new ContentReplacer($this->testDir, 'old_text', 'new_text');
        $replacer->replace();

        // Check if file in excluded directory remains unchanged
        $this->assertStringContainsString(
            'old_text',
            file_get_contents($this->testDir . '/vendor/test_file.txt')
        );
    }

    public function testCustomExcludedDirectories(): void
    {
        // Create custom excluded directory
        mkdir($this->testDir . '/custom_exclude');
        file_put_contents(
            $this->testDir . '/custom_exclude/test_file.txt',
            'a file containing old_text'
        );

        $replacer = new ContentReplacer($this->testDir, 'old_text', 'new_text');
        $replacer->setExcludedDirectories(['custom_exclude']);
        $replacer->replace();

        // Check if file in custom excluded directory remains unchanged
        $this->assertStringContainsString(
            'old_text',
            file_get_contents($this->testDir . '/custom_exclude/test_file.txt')
        );
    }
} 