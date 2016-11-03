<?php
namespace WebSockets\Service\Logger\Adapter;

use WebSockets\Service\Logger\BaseLogger;

/**
 * Class File
 * @package    WebSockets\Service\Logger\Adapter
 * @since      PHP >=5.6
 * @version    v3.2.1
 * @author     Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright  Stanislav WEB
 * @license    Zend Framework GUI license (New BSD License)
 * @filesource /vendor/stanislav-web/zf2-websocket-server-factory/src/Service/Logger/Adapter/File.php
 */
class File extends BaseLogger {

	/**
	 * Filename
	 *
	 * @var string $filename
	 */
	protected $filename = '';

	/**
	 * Setup logger configuration
	 *
	 * @param string $filename
	 */
	public function __construct ( $filename ) {
		$this->filename = $filename;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed  $level
	 * @param string $message
	 * @param array  $context
	 *
	 * @return void
	 */
	public function log ( $level, $message, array $context = array () ) {
		$line = $this->formatMessage ( $level, $message, $context );

		if ( false === is_dir ( dirname ( $this->filename ) ) ) {
			mkdir ( dirname ( $this->filename ), 0777, true );
		}
		$line = preg_replace('/\x1b(\[|\(|\))[;?0-9]*[0-9A-Za-z]/', "", $line);
		if ( file_put_contents ( $this->filename, $line, FILE_APPEND | LOCK_EX ) === false ) {
			throw new \Exception( 'Unable to write to the log file.' );
		}
	}
}