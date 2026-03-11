# 📊 PHP Code Stats

Command-line tool for analyzing source code metrics: files, lines, and characters.

---

## Installation

Install as a development dependency via Composer:

```bash
composer require --dev developer-samuel/php-code-stats
```

## Configuration

A configuration file codestats-analyzer.xml is required in the project root. It defines:

- file_extensions - file types to include.
- ignored_dirs - directories to skip.

You can bootstrap it automatically from the library:

```bash
php -r "require 'vendor/autoload.php'; DeveloperSamuel\PhpCodeStats\Installer::copyConfig();"
```

Or copy it manually from vendor/developer-samuel/php-code-stats/config/analyzer.xml to your project root.

Example **codestats-analyzer.xml**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<analyzer>
    <file_extensions>
        <ext>php</ext>
        <ext>js</ext>
        <ext>css</ext>
    </file_extensions>
    <ignored_dirs>
        <dir>bin</dir>
        <dir>vendor</dir>
        <dir>node_modules</dir>
        <dir>public</dir>
    </ignored_dirs>
</analyzer>
```

## Usage

Three commands are available:

```bash
# Count stats
php vendor/bin/count-stats

# Count files
php vendor/bin/count-files

# Count lines
php vendor/bin/count-lines

# Count characters
php vendor/bin/count-chars
```

Commands automatically use the codestats-analyzer.xml in your project root.

## Notes

- The config file can be **edited** to add/remove extensions or ignored directories.
- Using `php -r "Installer::copyConfig();"` is enough to bootstrap the default config. No need to write manually unless you want custom changes.
- Ideal for integrating into CI/CD or dev workflow to monitor project metrics.
