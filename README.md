# TYPO3 Logformatter

![Psalm coverage](https://shepherd.dev/github/sudhaus7/typo3-logformatter/coverage.svg)

This extension provides a CLI tool for both the typo3 and the typo3cms tools to parse, search and format TYPO3 logfiles.

Parts are colored, additional information from the logger will be displayed in a table, and the stacktrace is expanded for better reading. Additionally, if your Terminal supports it, the Filename in the Stacktrace can be clicked and the file opens in your editor

## Help

<pre>
Description:
  Formats TYPO3 Logfiles

Usage:
  logformatter [options] [--] [<file>...]

Arguments:
  file                                             Filename or - for STDIN

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
