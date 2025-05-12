# Text Replacer

A PHP package for replacing text in directory and file contents.

## Features

- Replace text in file and directory names
- Replace text in file contents
- Process subdirectories recursively
- Customize excluded directories

## Installation

```bash
composer require ismailocal/text-replacer
```

## Usage

### Command Line Usage

```bash
./vendor/bin/text-replacer [directory] search_text replace_text
```

### PHP Code Usage

```php
use IsmailOcal\TextReplacer\TextReplacer;

$replacer = new TextReplacer('./project_directory', 'old_text', 'new_text');

// Optional: Customize excluded directories
$replacer->setExcludedDirectories(['vendor', 'node_modules']);

// Start the process
$replacer->execute();
```

## License

MIT 