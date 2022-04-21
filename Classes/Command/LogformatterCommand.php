<?php

namespace Sudhaus7\Logformatter\Command;

use Sudhaus7\Logformatter\Format\FilelinkFormat;
use Sudhaus7\Logformatter\Format\FormatInterface;
use Sudhaus7\Logformatter\Format\LineFormat;
use Sudhaus7\Logformatter\Pattern\PatternInterface;
use Sudhaus7\Logformatter\Pattern\StacktracePattern;
use Sudhaus7\Logformatter\Pattern\Typo3LogPattern;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Finder\Finder;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use function array_merge;
use function basename;
use function is_object;
use function is_resource;

class LogformatterCommand extends Command {

	/**
	 * @var string[]
	 */
	protected static $IGNORE_PATTERNS = [
		'typo3_deprecations*'
	];
	/**
	 * @var InputInterface
	 * @psalm-suppress PropertyNotSetInConstructor
	 */
	private $input;
	/**
	 * @var OutputInterface
	 * @psalm-suppress PropertyNotSetInConstructor
	 */
	private $output;
	/**
	 * @var int
	 */
	private $lines = 0;
	/**
	 * @var int
	 */
	private $cols = 0;
	/**
	 * @var bool
	 */
	private $pager = false;
	/**
	 * @var int
	 */
	private $linecounter = 0;
	/**
	 * @var PatternInterface
	 */
	private $pattern;
	/**
	 * @var FormatInterface
	 */
	private $format;
	/**
	 * @var PatternInterface
	 */
	private $stacktracePattern;
	/**
	 * @var FormatInterface
	 */
	private $filelinkFormat;

	public function __construct(
		string $name = null,
		PatternInterface $pattern = null,
		FormatInterface $format = null,
		PatternInterface $stacktracePattern = null,
		FormatInterface $filelinkFormat = null
	) {
		if (!$pattern instanceof PatternInterface) {
			/** @var PatternInterface $pattern */
			$pattern = GeneralUtility::makeInstance( Typo3LogPattern::class);
		}
		if (!$format instanceof FormatInterface) {
			/** @var FormatInterface $format */
			$format = GeneralUtility::makeInstance( LineFormat::class);
		}
		if (!$stacktracePattern instanceof PatternInterface) {
			/** @var PatternInterface $stacktracePattern */
			$stacktracePattern = GeneralUtility::makeInstance( StacktracePattern::class);
		}
		if (!$filelinkFormat instanceof FormatInterface) {
			/** @var FormatInterface $filelinkFormat */
			$filelinkFormat = GeneralUtility::makeInstance( FilelinkFormat::class);
		}

		/**
		 * @psalm-suppress MixedArrayAccess
		 */
		if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']) && is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']) && !empty($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter'])) {

			if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']['format'])) {
				/** @var string $className */
				$className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']['format'];
				if (class_exists($className) && is_array(class_implements($className)) && in_array(FormatInterface::class,class_implements($className))) {
					/** @var FormatInterface $format */
					$format = GeneralUtility::makeInstance( $className );
				}
			}
			if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']['filelinkFormat'])) {
				/** @var string $className */
				$className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']['filelinkFormat'];
				if (class_exists($className) && is_array(class_implements($className)) && in_array(FormatInterface::class,class_implements($className))) {
					/** @var FormatInterface $filelinkFormat */
					$filelinkFormat = GeneralUtility::makeInstance( $className );
				}
			}
			if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']['stacktracePattern'])) {
				/** @var string $className */
				$className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']['stacktracePattern'];
				if (class_exists($className) && is_array(class_implements($className)) && in_array(PatternInterface::class,class_implements($className))) {
					/** @var PatternInterface $stacktracePattern */
					$stacktracePattern = GeneralUtility::makeInstance( $className );
				}
			}
			if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']['pattern'])) {
				/** @var string $className */
				$className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']['pattern'];
				if (class_exists($className) && is_array(class_implements($className)) && in_array(PatternInterface::class,class_implements($className))) {
					/** @var PatternInterface $pattern */
					$pattern = GeneralUtility::makeInstance( $className );
				}
			}
		}

		$this->pattern = $pattern;
		$this->format = $format;
		$this->stacktracePattern = $stacktracePattern;
		$this->filelinkFormat = $filelinkFormat;
		parent::__construct( $name );
	}

	/**
	 * @inheritDoc
	 */
	protected function configure() : void
	{
		$this->setDescription('Tool to pretty print and format Logfile entries');

		$this->addArgument('file', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'Filename or - for STDIN');

		$this->addOption( 'search',null,InputOption::VALUE_REQUIRED,'Search in message for this keyword');

		$this->addOption( 'request',null,InputOption::VALUE_REQUIRED,'display only this request');

		$this->addOption( 'component',null,InputOption::VALUE_REQUIRED,'search within component');

		$this->addOption( 'level',null,InputOption::VALUE_REQUIRED,'show only this error level');

		$this->addOption( 'max-buffer',null,InputOption::VALUE_OPTIONAL,'use this max buffer size in bytes for each line (default: 16384)');

		$this->addOption( 'show-meta','m',InputOption::VALUE_NONE,'Show additional information / meta information');
		$this->addOption( 'show-stacktrace','s',InputOption::VALUE_NONE,'Show the stacktrace');

		$this->addOption( 'hide-vendor',null,InputOption::VALUE_NONE,'Hide vendor paths in stacktrace, implies --show-stacktrace');

		$this->addOption( 'pager',null,InputOption::VALUE_NONE,'(EXPERIMENTAL) paging');
		$this->addOption( 'ignore-file-pattern',null,InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,'Logfile filename patterns to ignore (Default '.implode(',',self::$IGNORE_PATTERNS).')');

		// date from / to

		$this->setHelp('
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
 
 Don\'t parse logfiles matching a certain file pattern (multiple)
 ./vendor/bin/typo3 logformatter --ignore-file-pattern="*def.log"
 
 Parsing a specific file
 ./vendor/bin/typo3 logformatter var/log/typo3_0fb8cbec8e.log
 
 Using stdin as input
 tail -f var/log/typo3_0fb8cbec8e.log | ./vendor/bin/typo3 logformatter -  
 
 If lines are very long (or memory is limited; opposite case) line buffer can be specified
 ./vendor/bin/typo3 logformatter --max-buffer=1000000
 
		');



	}


	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int
	 */
	protected function execute(InputInterface $input, OutputInterface $output) : int
	{
		$this->input = $input;
		$this->output = $output;

		$this->format->configOutput($output);
		$this->filelinkFormat->configOutput($output);

		/**
		 * @var string[]
		 * @psalm-suppress MixedArgument
		 */
		$ignorePatterns = array_merge( $input->getOption( 'ignore-file-pattern'),self::$IGNORE_PATTERNS);

		if ($input->getOption( 'pager')) {
			$this->pager = true;
			$this->resetTTY();
		}

		/** @var string[] $files */
		$files = $input->getArgument( 'file');

		if (in_array('-',$files)) {
			$this->pager = false;
			$this->handleFile( 'php://stdin' );
		} else {
			/** @var Finder $finder */
			$finder = GeneralUtility::makeInstance( Finder::class);

			if (empty($files)) {
				$files = [Environment::getVarPath() . '/log/*.log'];
			}
			foreach ($files as $file) {

				$filename  = basename( $file );
				$directory = dirname( $file );

				$finder->name( $filename );
				$finder->notName( $ignorePatterns );

				/**
				 * @psalm-suppress MixedAssignment
				 */
				foreach ( $finder->in( $directory ) as $thefile ) {
					/**
					 * @psalm-suppress MixedMethodCall
					 */
					$this->handleFile( (string) $thefile->getRealPath() );
				}
			}
		}
		return 0;
	}

	private function resetTTY() : void
	{
		exec('tput clear');
	}

	/**
	 * @param string $file
	 */
	protected function handleFile( string $file): void
	{
		$fp = fopen( $file, 'r' );
		$maxBufferLength = (int)($this->input->getOption('max-buffer') ?? 16 * 1024);

		if ( is_resource( $fp ) ) {
			$filename = basename($file);
			while ( $buf = fgets( $fp, $maxBufferLength ) ) {
				//echo "---".trim($buf)."---\n";

				if (
					$this->input->getOption( 'search' ) !== null &&
					stripos( $buf, (string) $this->input->getOption( 'search' ) ) === false
				) {
					continue;
				}

				$matches = $this->pattern->getMatches( $buf);

				if (empty($matches)) {
					continue;
				}

				if ( $this->input->getOption( 'request' ) !== null && $this->input->getOption( 'request' ) !== $matches['request'] ) {
					continue;
				}
				if ( $this->input->getOption( 'component' ) !== null && $this->input->getOption( 'component' ) !== $matches['component'] ) {
					continue;
				}
				if ( $this->input->getOption( 'level' ) !== null && $this->input->getOption( 'level' ) !== $matches['level'] ) {
					continue;
				}

				$this->writeln( $this->format->formatLine( $matches, $filename ) );
				$this->writeln( $matches['msg'] );


				if ( $this->input->getOption( 'show-stacktrace' ) || $this->input->getOption( 'show-meta' ) ) {

					$meta = [];
					$stacktrace = [];

					/** @var (string|array)[] $json */
					$json = json_decode( $matches['json'], true );
					if ( $json ) {
						foreach ( $json as $key => $line ) {
							if ( $key === 'exception') {
								if ($this->input->getOption( 'show-stacktrace' )) {
									/** @var string $line */
									$exceptionlines = explode( "\n", $line );
									foreach ( $exceptionlines as $exline ) {
										if ( substr( $exline, 0, 1 ) === '#' ) {

											$m = $this->stacktracePattern->getMatches( $exline );

											$show = (count( $m ) === 5 || isset($m['index']));
											if ( $show && $this->input->getOption( 'hide-vendor' )) {
												$show = false;
												if ( strpos( $m['file'], '/vendor/' ) === false ) {
													$show = true;
												}
											}
											if ( $show ) {

												$stacktrace[] = $this->filelinkFormat->formatLine($m);
											}

										} else {
											$stacktrace[] = $exline;
										}
									}
								}

							} else {
								if ( $this->input->getOption( 'show-meta' ) ) {
									if (is_array($line)) {
										$meta[] = [$key,print_r($line,true)];
									}  else {
										$meta[] = [$key,$line];
									}


								}
							}
						}
					}

					if (!empty($meta)) {
						/** @var Table $table */
						$table = GeneralUtility::makeInstance( Table::class,$this->output);
						$table->setRows( $meta );
						$table->render();
					}
					if (!empty($stacktrace)) {
						foreach($stacktrace as $line) {
							$this->writeln( $line);
						}
					}
				}
			}
		}
	}

	private function writeln(?string $line) : void
	{
		if (empty($line)) {
			return;
		}

		if ($this->pager) {
			$this->getTTY();
			$lines = (int)ceil(strlen($line)/$this->cols);
			if ($this->linecounter+$lines > $this->lines) {
				$question = new Question(
					'<question>continue</question>',
					false
				);
				$question->setMaxAttempts( 1 );
				/** @var QuestionHelper $helper */
				$helper = $this->getHelper( 'question' );

				$answer = (string)$helper->ask( $this->input, $this->output, $question );
				if ( $answer === 'q' ) {
					exit( 0 );
				}
				$this->linecounter = 0;
			}
			$this->linecounter = $this->linecounter+$lines;
		}
		$this->output->writeln( $line);


	}

	private function getTTY() : void
	{

		$this->cols = (int)exec('tput cols');
		$this->lines = (int)exec('tput lines');
	}

}
