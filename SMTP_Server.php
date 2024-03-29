<?php
/**
 * SMTP_Server
 *
 * @author Jason Johnson <jason@php-smtp.org>
 * @copyright Copyright (c) 2008, Jason Johnson
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @version 1.0
 * @package php-smtp
 */

class SMTP_Server {
	var $socket;
	var $host;
	var $port;
	var $remote;
	var $api;
	
	/**
	 * Constructor, no required parameters if binding to the localhost interface.
	 *
	 * @param string $host IP address to bind the server to, defaults to 127.0.0.1
	 * @param int $port Port to user on the specified host address, defaults to 25
	 */
	function SMTP_Server($host = null, $port = null) {
		global $api;
		
		$this->host = $host?$host:SMTP_HOST;
		$this->port = $port?$port:SMTP_PORT;
		$this->domains = array();
		
		$this->api = &$api;
		
		$this->socket = new SMTP_Server_Socket();
		$this->socket->bind($this->host, $this->port);
		$this->socket->listen();
	}
	
	/**
	 * Enters an infinite loop and listens for new connections from remote hosts.
	 */
	function run() {
		while(true) {
			$this->remote = $this->socket->accept();
			
			$session =& new SMTP_Server_Session($this->remote);
			$session->run();
			
			$session = null;
		}
		
		$this->socket->close();
	}
}
?>