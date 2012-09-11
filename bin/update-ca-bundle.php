#!/usr/bin/env php
<?php

require_once __DIR__ . '/../autoload_register.php';

$help = <<<help
Sslurp Root CA Bundle Updater

Usage:
 {$argv[0]} [-o output_file]

Options
 -o\tPath/filename to the file to (over)write he update root CA bundle. Defaults to - (stdout).

Sslurp home page: https://github.com/EvanDotPro/Sslurp
help;

$opts = getopt('o::');
if (isset($opts['o']) && is_array($opts['o'])) {
    echo "WARNING: Multiple output paths given; ignoring all but the first value provided: `{$opts['o'][0]}`\n\n";
    $opts['o'] = $opts['o'][0];
} elseif (!isset($opts['o']) && isset($argv[1])) {
    $opts['o'] = $argv[1];
}

$caBundleBuilder = new Sslurp\RootCaBundleBuilder();
$caBundle        = $caBundleBuilder->getUpdatedRootCaBundle();

if (!$caBundle) {
    echo "Sorry, there was an error building the latest root CA bundle.\n";
    exit(1);
}

if (!isset($opts['o']) || $opts['o'] == '-') {
    echo $caBundle;
} else {
    file_put_contents($opts['o'], $caBundle);
    echo "Updated root CA bundle written to {$opts['o']}.\n";
}
