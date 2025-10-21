<?php

declare(strict_types=1);

namespace Gaphub;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core plugin class
 * 
 * @since 1.0.0
 */
final class Plugin {

	private static $instance = null;

	public $settings;

	public function __construct() {
		$this->load_dependencies();
		$this->init_hooks();
	}

	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function load_dependencies() {
		$this->settings = new Admin\Settings();
	}

	private function init_hooks() {
		$this->settings->register_hooks();
	}
}
