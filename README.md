# TYPO3 Logformatter

[![Latest Stable Version](https://poser.pugx.org/sudhaus7/logformatter/v/stable.svg)](https://extensions.typo3.org/extension/logformatter/)
[![TYPO3 13](https://img.shields.io/badge/TYPO3-13-orange.svg)](https://get.typo3.org/version/12)
[![TYPO3 12](https://img.shields.io/badge/TYPO3-12-orange.svg)](https://get.typo3.org/version/12)
[![Total Downloads](https://poser.pugx.org/sudhaus7/logformatter/d/total.svg)](https://packagist.org/packages/sudhaus7/logformatter)
[![Monthly Downloads](https://poser.pugx.org/sudhaus7/logformatter/d/monthly)](https://packagist.org/packages/sudhaus7/logformatter)
![PHPSTAN:Level 9](https://img.shields.io/badge/PHPStan-level%209-brightgreen.svg?style=flat])
![build:passing](https://img.shields.io/badge/build-passing-brightgreen.svg?style=flat])
![Psalm coverage](https://shepherd.dev/github/sudhaus7/typo3-logformatter/coverage.svg)

This extension provides a CLI tool for both the typo3 and the typo3cms tools to parse, search and format TYPO3 logfiles.

Parts are colored, additional information from the logger will be displayed in a table, and the stacktrace is expanded for better reading. Additionally, if your Terminal supports it, the Filename in the Stacktrace can be clicked and the file opens in your editor

## Changelog

1.6.0
- made available for TYPO3 13
- dropped support for TYPO3 9, 10 and 11
- dropped support for PHP older than 8.0

1.5.1
- Added ConsoleLogger Logger. This is basically a copy of the Symfony Console Logger, with the difference that all Meta Data is expanded, and no message line substitution has been made
1.5.0
- Nothing changed, just marked for TYPO3 12 compatibility

1.4.0
- New feature: Adding the possibility to add a log-line in the TYPO3 Log per request to log the current requests URL
- New feature: Providing a LogProcessor to add the current request url as meta data to arbitrary logger configurations

1.3.0
- Introduction to the environment variables LOGFORMATTER_FILELINKFORMATTER, LOGFORMATTER_LINEFORMATTER, LOGFORMATTER_STACKTRACEPATTERN and LOGFORMATTER_LOGPATTERN
- Refactoring the dependency injection to use factories and proper TYPO3 DI instead of messy code (thanks to Daniel Siepmann for his great blog post on this topic)
- Clarifying usage of the STDIN option to use with tail -f
- Codestyle cleanup

1.2.0 Introduction of the environment variable LOGFORMATTER_MAX_BUFFER to configure the max-line-buffer (--max-buffer)

## Help

<pre>
Description:
  Formats TYPO3 Logfiles

Usage:
  logformatter [options] [--] [<file>...]

Arguments:
  file                                             Filename or - for STDIN (for example with tail -f)

Options:
      --search=SEARCH                              Search in message for this keyword
      --request=REQUEST                            display only this request
      --component=COMPONENT                        search within component
      --level=LEVEL                                show only this error level
  -m, --show-meta                                  Show additional information / meta information
  -s, --show-stacktrace                            Show the stacktrace
      --hide-vendor                                Hide vendor paths in stacktrace, implies --show-stacktrace
      --pager                                      (EXPERIMENTAL) paging
      --ignore-file-pattern[=IGNORE-FILE-PATTERN]  Logfile filename patterns to ignore (Default typo3_deprecations*) (multiple values allowed)
  -h, --help                                       Display help for the given command. When no command is given display help for the list command
  -q, --quiet                                      Do not output any message
  -V, --version                                    Display this application version
      --ansi|--no-ansi                             Force (or disable --no-ansi) ANSI output
  -n, --no-interaction                             Do not ask any interactive question
  -v|vv|vvv, --verbose                             Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:

   Parse, search, format and display TYPO3 Logfiles.

   It is possible to combine parameters. By default only the logline will be shown, the meta information and stacktrace will be hidden. Keywords can be searched and the output can be filtered by request, level and component.

   Usage:

   This will display all logs which are located in var/logs/
   ./vendor/bin/typo3 logformatter

   In this example the output will be filtered according to a certain request ID
   ./vendor/bin/typo3 logformatter --request=928d81f2d604e

   In this example the output will be filtered according to a certain component
   ./vendor/bin/typo3 logformatter --component=TYPO3.CMS.Core.Error.ErrorHandler

   Filtering the output by error level
   ./vendor/bin/typo3 logformatter --level=WARNING

   Searching for a keyword, for example an Oops error code
   ./vendor/bin/typo3 logformatter --search=2021110100165236c2ab3f

   Displaying meta information given to the log by the process (will displayed in a table)
   ./vendor/bin/typo3 logformatter --show-meta
   ./vendor/bin/typo3 logformatter -m

   Displaying the stack-trace in an expanded, readable form (one line per stack)
   ./vendor/bin/typo3 logformatter --show-stacktrace
   ./vendor/bin/typo3 logformatter -s

   Skipping stacks pointing to /vendor in the stacktrace to shorten it
   ./vendor/bin/typo3 logformatter --show-stacktrace --hide-vendor

   Don't parse logfiles matching a certain file pattern (multiple)
   ./vendor/bin/typo3 logformatter --ignore-file-pattern="*def.log"

   Parsing a specific file
   ./vendor/bin/typo3 logformatter var/log/typo3_0fb8cbec8e.log

   Using stdin as input
   tail -f var/log/typo3_0fb8cbec8e.log | ./vendor/bin/typo3 logformatter -
</pre>

## Example output

![Example](https://raw.githubusercontent.com/sudhaus7/typo3-logformatter/main/.github/example.png)

## Using with PHPStorm
While PHPStorm will open the file when you click on the filename in a stacktrace, it will not jump to the line. This can be achieved by injecting a different formatter for the file-link.

This could be done by overriding the Services.yaml or by simply adding the following line to your AdditionalConfiguration.php

<pre>
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']['filelinkFormat'] = \Sudhaus7\Logformatter\Format\PhpstormlinkFormat::class;
</pre>

Now the link will contain the phpstorm:// namespace and will open the file and jump to the given line.

## Using environment variables or $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'] options
as we see in the previous example there are some configuration options and environment variables available to modify and extend the logformatter:

### LOGFORMATTER_MAX_BUFFER

This environment variable substitutes the parameter --max-buffer. If you have large stacktraces increase the Buffer with this variable. It accepts an integer number representing bytes to be read. If you are missing lines or stacktraces, use this variable.

E.q. ```export LOGFORMATTER_MAX_BUFFER=10000000```

### LOGFORMATTER_FILELINKFORMATTER

This is the same as setting ```$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']['filelinkFormat'] = \Sudhaus7\Logformatter\Format\PhpstormlinkFormat::class;```

The notation for this variable is the dot-notation

E.q. ```export LOGFORMATTER_FILELINKFORMATTER=Sudhaus7.Logformatter.Format.PhpstormlinkFormat```

This will change the Linkformatter for Links in the stacktrace.

### LOGFORMATTER_LINEFORMATTER

This is the same as setting ```$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']['format'] = \Sudhaus7\Logformatter\Format\LineFormat::class;```

The notation for this variable is the dot-notation

E.q. ```export LOGFORMATTER_FILELINKFORMATTER=Sudhaus7.Logformatter.Format.LineFormat``` (default)

This specifies how a Log-line is formatted in the output.

### LOGFORMATTER_LOGPATTERN

This is the same as setting ```$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']['pattern'] = \Sudhaus7\Logformatter\Pattern\Typo3LogPattern::class;```

The notation for this variable is the dot-notation

E.q. ```export LOGFORMATTER_FILELINKFORMATTER=Sudhaus7.Logformatter.Pattern.Typo3LogPattern``` (default)

This specifies how a Log-line is parsed. With changing this other Logformats than the TYPO3 format could be parsed

### LOGFORMATTER_LOGPATTERN

This is the same as setting ```$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']['stacktracePattern'] = \Sudhaus7\Logformatter\Pattern\StacktracePattern::class;```

The notation for this variable is the dot-notation

E.q. ```export LOGFORMATTER_FILELINKFORMATTER=Sudhaus7.Logformatter.Pattern.StacktracePattern``` (default)

This specifies how a Stacktrace is parsed. With changing this other Formats than the TYPO3 format could be parsed

### Adding the request url to the log file in general

This feature will add a log line per request containing the current request url. This gives the advantage that when you search all lines for a certain request with the --request=REQUEST option you will get the URL that produced this specific request. This is a possible way to get informations in general or when you are monitoring not specific log entries, but log entries in general

To enable this feature either check the logrequesturl option in the TYPO3 backend in the extension setup under Settings -> Extension Configuration -> logformatter -> 'Enable Middleware to log the request URL per Request to the TYPO3 logfile'

OR

if you have the typo3cms CLI tool installed, execute the following command in your shell in your project root:
<pre>
./vendor/bin/typo3cms configuration:set EXTENSIONS/logformatter/logrequesturl 1
</pre>

to disable use the same command with 0 at the end:
<pre>
./vendor/bin/typo3cms configuration:set EXTENSIONS/logformatter/logrequesturl 0
</pre>

### Using the LogProcessor

If you want to debug or monitor specific logging configurations using this log processor to add the request url to the meta information of a specific log entry might be a better way than adding the url in general as a separate log entry (previous entry).

To add the LogProcessor add this for example into your AdditionConfiguration.php:
<pre>
$GLOBALS['TYPO3_CONF_VARS']['LOG']['EXAMPLE']['EXTENSION']['NAMESPACE']['CLASS']['processorConfiguration'] = [
    \TYPO3\CMS\Core\Log\LogLevel::ERROR => [
        \Sudhaus7\Logformatter\Log\Processor\UrlProcessor::class => []
    ],
];
</pre>

The configuration is similar to the initial writerConfiguration. See as well [the official Documantation](https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/Logging/Configuration/Index.html)
