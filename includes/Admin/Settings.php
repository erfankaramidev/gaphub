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

	private $fields = [
		'primary_color'          => [
			'label'   => 'Primary Color',
			'type'    => 'color',
			'default' => '#2b8cff',
		],
		'comment_bg_color'       => [
			'label'   => 'Comment Background',
			'type'    => 'color',
			'default' => '#f9f9f9',
		],
		'base_font_size_px'      => [
			'label'   => 'Base Font Size (px)',
			'type'    => 'px',
			'default' => 16,
			'attrs'   => [ 'min' => 8, 'max' => 72 ],
		],
		'heading_font_size_px'   => [
			'label'   => 'Heading Font Size (px)',
			'type'    => 'px',
			'default' => 24,
			'attrs'   => [ 'min' => 10, 'max' => 120 ],
		],
		'field_border_radius_px' => [
			'label'   => 'Field Border Radius (px)',
			'type'    => 'px',
			'default' => 4,
			'attrs'   => [ 'min' => 0, 'max' => 100 ],
		],
		'gap_px'                 => [
			'label'   => 'Gap (px)',
			'type'    => 'px',
			'default' => 12,
			'attrs'   => [ 'min' => 0, 'max' => 200 ],
		],
		'input_padding_px'       => [
			'label'   => 'Input Padding (px)',
			'type'    => 'px',
			'default' => 8,
			'attrs'   => [ 'min' => 0, 'max' => 100 ],
		],
	];

	public function register_hooks() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
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
		<?php
	}

	public function register_settings() {
		register_setting( $this->option_group, $this->option_name, [
			'sanitize_callback' => [ $this, 'sanitize' ]
		] );

		add_settings_section(
			'gh_style_section',
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
				'gh_style_section',
				[
					'name' => $name,
					'meta' => $meta
				]
			);
		}
	}

	public function render_field( $args ) {
		$name    = $args['name'];
		$meta    = $args['meta'];
		$default = $meta['default'] ?? '';
		$value   = $this->get_option( $name, $default );

		if ( $meta['type'] === 'color' ) {
			printf(
				'<input type="color" name="%s" value="%s" class="color-picker" data-default-color="%s">',
				$name,
				esc_attr( $value ),
				esc_attr( $default )
			);

			return;
		} elseif ( $meta['type'] === 'px' ) {
			printf(
				'<input type="number" name="%s" value="%s" min="%s" max="%s">',
				$name,
				esc_attr( $value ),
				esc_attr( $meta['attrs']['min'] ),
				esc_attr( $meta['attrs']['max'] )
			);

			return;
		}

		printf(
			'<input type="text" name="%s" value="%s">',
			$name,
			esc_attr( $value ),
		);
	}

	public function sanitize( $input ) {
		$sanitized = [];

		foreach ( $this->fields as $key => $meta ) {
			if ( ! isset( $input[ $key ] ) ) {
				return;
			}

			$value = $input[ $key ];

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

		return $sanitized;
	}

	public function get_option( $key, $default = '' ) {
		$options = get_option( $this->option_name );

		return ( isset( $options[ $key ] ) && ! empty( $options[ $key ] ) ) ? $options[ $key ] : $default;
	}
}
