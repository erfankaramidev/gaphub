<?php

declare(strict_types=1);

namespace Gaphub\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin settings class
 * 
 * @since 1.0.0
 */
final class Settings {

	private $option_group = 'gh_settings_group';
	private $option_name  = 'gh_settings';
	private $menu_slug    = 'gaphub-settings';

	/**
	 * @var array Defines all settings fields.
	 */
	private $fields = [
		// ====== Style Settings Section ======
		'primary_color'       => [
			'label'   => 'Primary Color',
			'type'    => 'color',
			'default' => '#2b8cff',
			'section' => 'styles',
		],
		'primary_color_hover' => [
			'label'   => 'Primary Color Hover',
			'type'    => 'color',
			'default' => '#167fffff',
			'section' => 'styles',
		],
		'comment_bg_color'    => [
			'label'   => 'Comment Background',
			'type'    => 'color',
			'default' => '#f9f9f9',
			'section' => 'styles',
		],
		'border_color'        => [
			'label'   => 'Border Color',
			'type'    => 'color',
			'default' => '#e5e5e5',
			'section' => 'styles',
		],
		'heading_font_size'   => [
			'label'   => 'Heading Font Size (px)',
			'type'    => 'px',
			'default' => 24,
			'attrs'   => [ 'min' => 10, 'max' => 120 ],
			'section' => 'styles',
		],
		'border_radius'       => [
			'label'   => 'Border Radius (px)',
			'type'    => 'px',
			'default' => 4,
			'attrs'   => [ 'min' => 0, 'max' => 100 ],
			'section' => 'styles',
		],
		'gap'                 => [
			'label'   => 'Gap (px)',
			'type'    => 'px',
			'default' => 12,
			'attrs'   => [ 'min' => 0, 'max' => 200 ],
			'section' => 'styles',
		],
	];

	public function register_hooks() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
	}

	public function enqueue_admin_scripts( $hook ) {
		if ( $hook === "settings_page_{$this->menu_slug}" ) {
			// Enqueue WordPress color picker
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
		}
	}

	public function add_admin_menu() {
		add_options_page(
			__( 'Gaphub', 'gaphub' ),
			__( 'Gaphub', 'gaphub' ),
			'manage_options',
			$this->menu_slug,
			[ $this, 'render_settings_page' ]
		);
	}

	public function render_settings_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Gaphub Settings', 'gaphub' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( $this->option_group );
				do_settings_sections( $this->menu_slug );
				submit_button();
				?>
			</form>
		</div>
		<script>
			jQuery(document).ready(function ($) {
				$('.gh-color-picker').wpColorPicker();
			});
		</script>
		<?php
	}

	public function register_settings() {
		register_setting( $this->option_group, $this->option_name, [
			'sanitize_callback' => [ $this, 'sanitize' ]
		] );

		add_settings_section(
			'gh_styles_section',
			__( 'Default Styles', 'gaphub' ),
			'__return_null',
			$this->menu_slug
		);

		foreach ( $this->fields as $name => $meta ) {
			add_settings_field(
				$name,
				__( $meta['label'], 'gaphub' ),
				[ $this, 'render_field' ],
				$this->menu_slug,
				'gh_' . $meta['section'] . '_section',
				[
					'name' => $name,
					'meta' => $meta
				]
			);
		}
	}

	/**
	 * Render each field based on its type
	 */
	public function render_field( $args ) {
		$name    = $args['name'];
		$meta    = $args['meta'];
		$default = $meta['default'] ?? '';
		$value   = $this->get_option( $name, $default );
		$key     = "{$this->option_name}[{$name}]";
		$desc    = $meta['desc'] ?? '';

		switch ( $meta['type'] ) {
			case 'color':
				printf(
					'<input type="color" name="%s" value="%s" class="gh-color-picker" data-default-color="%s">',
					esc_attr( $key ),
					esc_attr( (string) $value ),
					esc_attr( (string) $default )
				);
				break;
			case 'px':
				$min = $meta['attrs']['min'] ?? 0;
				$max = $meta['attrs']['max'] ?? 999;

				printf(
					'<input type="number" name="%s" value="%s" min="%s" max="%s">',
					esc_attr( $key ),
					esc_attr( $value ),
					esc_attr( (string) $min ),
					esc_attr( (string) $max )
				);
				break;
			case 'text':
			default:
				printf(
					'<input type="text" name="%s" value="%s" class="regular-text">',
					esc_attr( $key ),
					esc_attr( (string) $value ),
				);
		}

		if ( $desc ) {
			printf(
				'<p class="description">%s</p>',
				esc_html( $desc )
			);
		}
	}

	/**
	 * Sanitize each field based on its type
	 */
	public function sanitize( $input ) {
		$sanitized = [];
		$options   = get_option( $this->option_name, [] );

		foreach ( $this->fields as $key => $meta ) {
			$value = $input[ $key ] ?? $meta['default'];

			switch ( $meta['type'] ) {
				case 'color':
					$sanitized[ $key ] = sanitize_hex_color( $value );
					break;
				case 'px':
					$value = (int) $value;
					$min = $meta['attrs']['min'] ?? 0;
					$max = $meta['attrs']['max'] ?? 9999;
					$sanitized[ $key ] = max( $min, min( $value, $max ) );
					break;
				default:
					$sanitized[ $key ] = sanitize_text_field( $value );
					break;
			}
		}

		return array_merge( $options, $sanitized );
	}

	public function get_option( $key, $default = '' ) {
		$options = get_option( $this->option_name, [] );

		if ( ! isset( $options[ $key ] ) ) {
			return $this->fields[ $key ]['default'] ?? $default;
		}

		return $options[ $key ];
	}
}
