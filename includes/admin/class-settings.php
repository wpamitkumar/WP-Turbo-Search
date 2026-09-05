<?php
namespace WPTS\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Centralised settings store for WP Turbo Search.
 * Manages all option keys, typed casting, defaults, and sanitization.
 */
class Settings {

	public const KEYS = [
		'post_types',
		'enable_frontend_search',
		'debounce_ms',
		'results_per_page',
		'highlight_results',
		'search_in_meta',
		'index_thumbnails',
		'index_comments',
		'index_attachments',
		'index_woocommerce',
		'weight_title',
		'weight_excerpt',
		'weight_content',
		'weight_taxonomies',
		'weight_meta',
		'weight_comments',
		'enable_spelling_suggestions',
		'enable_synonyms',
		'enable_boolean_search',
		'enable_phrase_search',
		'search_engine',
		'typesense_host',
		'typesense_port',
		'typesense_protocol',
		'typesense_api_key',
		'typesense_collection',
		'elasticsearch_host',
		'elasticsearch_port',
		'elasticsearch_protocol',
		'elasticsearch_username',
		'elasticsearch_password',
		'elasticsearch_api_key',
		'elasticsearch_index',
		'cache_driver',
		'cache_ttl',
		'cache_stats',
		'redis_host',
		'redis_port',
		'redis_password',
		'redis_db',
		'memcached_host',
		'memcached_port',
		'cdn_cache_headers',
		'cdn_cache_ttl',
		'scheduled_reindex_enabled',
		'scheduled_reindex_interval',
		'scheduled_reindex_mode',
		'enable_voice_search',
		'results_layout',
		'enable_user_history',
		'enable_user_favorites',
		'enable_archive_live_filter',
		'tracking_enabled',
		'tracking_retention_days',
		'track_clicks',
		'ab_testing_enabled',
		'ab_debounce_b',
		'ab_theme_b',
		'enable_honeypot',
		'role_restrictions',
		'gdpr_anonymize_days',
		'enable_command_k_modal',
		'woocommerce_quick_add_to_cart',
		'enable_category_tabs_dropdown',
		'enable_vector_search',
		'vector_provider',
		'vector_api_key',
		'vector_model',
		'vector_endpoint',
		'vector_weight',
		'multisite_cross_search',
		'admin_auto_refresh',
	];

	public const DEFAULTS = [
		'post_types'                  => [ 'post', 'page' ],
		'enable_frontend_search'      => true,
		'debounce_ms'                 => 200,
		'results_per_page'            => 10,
		'admin_auto_refresh'          => 30,
		'highlight_results'           => true,
		'search_in_meta'              => false,
		'index_thumbnails'            => true,
		'index_comments'              => false,
		'index_attachments'           => true,
		'index_woocommerce'           => true,
		'weight_title'                => 10,
		'weight_excerpt'              => 5,
		'weight_content'              => 1,
		'weight_taxonomies'           => 4,
		'weight_meta'                 => 3,
		'weight_comments'             => 1,
		'enable_spelling_suggestions' => true,
		'enable_synonyms'             => true,
		'enable_boolean_search'       => true,
		'enable_phrase_search'        => true,
		'search_engine'               => 'mysql',
		'typesense_host'              => '',
		'typesense_port'              => '8108',
		'typesense_protocol'          => 'http',
		'typesense_api_key'           => '',
		'typesense_collection'        => 'wpts_posts',
		'elasticsearch_host'          => '',
		'elasticsearch_port'          => '9200',
		'elasticsearch_protocol'      => 'http',
		'elasticsearch_username'      => '',
		'elasticsearch_password'      => '',
		'elasticsearch_api_key'       => '',
		'elasticsearch_index'         => 'wpts_posts',
		'cache_driver'                => 'auto',
		'cache_ttl'                   => 300,
		'cache_stats'                 => false,
		'redis_host'                  => '',
		'redis_port'                  => 6379,
		'redis_password'              => '',
		'redis_db'                    => 0,
		'memcached_host'              => '',
		'memcached_port'              => 11211,
		'cdn_cache_headers'           => false,
		'cdn_cache_ttl'               => 300,
		'scheduled_reindex_enabled'   => false,
		'scheduled_reindex_interval'  => 'daily',
		'scheduled_reindex_mode'      => 'incremental',
		'enable_voice_search'         => true,
		'results_layout'              => 'list',
		'enable_user_history'         => true,
		'enable_user_favorites'       => true,
		'enable_archive_live_filter'  => false,
		'tracking_enabled'            => true,
		'tracking_retention_days'     => 90,
		'track_clicks'                => true,
		'ab_testing_enabled'          => false,
		'ab_debounce_b'               => 350,
		'ab_theme_b'                  => 'minimal',
		'enable_honeypot'             => true,
		'role_restrictions'           => [],
		'gdpr_anonymize_days'         => 30,
		'enable_command_k_modal'      => true,
		'woocommerce_quick_add_to_cart' => true,
		'enable_category_tabs_dropdown' => true,
		'enable_vector_search'        => false,
		'vector_provider'             => 'openai',
		'vector_api_key'              => '',
		'vector_model'                => 'text-embedding-3-small',
		'vector_endpoint'             => 'http://localhost:11434/api/embeddings',
		'vector_weight'               => 0.3,
		'multisite_cross_search'      => false,
	];

	public static function get_all(): array {
		$out = [];
		foreach ( self::KEYS as $key ) {
			$out[ $key ] = self::get( $key );
		}
		return $out;
	}

	public static function get( string $key, $default = null ) {
		$default = null !== $default ? $default : ( self::DEFAULTS[ $key ] ?? null );
		$raw     = get_option( "wpts_{$key}", '__WPTS_UNSET__' );

		if ( '__WPTS_UNSET__' === $raw ) {
			return $default;
		}

		return self::cast( $key, $raw );
	}

	public static function seed_defaults_if_missing(): void {
		foreach ( self::DEFAULTS as $key => $value ) {
			add_option( "wpts_{$key}", is_bool( $value ) ? ( $value ? 1 : 0 ) : $value );
		}
	}

	public static function reset(): void {
		foreach ( self::DEFAULTS as $key => $value ) {
			update_option( "wpts_{$key}", is_bool( $value ) ? ( $value ? 1 : 0 ) : $value );
		}

		if ( class_exists( '\WPTS\Indexer\ScheduledSync' ) ) {
			\WPTS\Indexer\ScheduledSync::reschedule();
		}

		do_action( 'wpts_settings_reset' );
	}

	public static function save( array $data ): void {
		foreach ( self::KEYS as $key ) {
			if ( ! array_key_exists( $key, $data ) ) {
				continue;
			}
			$value = self::sanitize( $key, $data[ $key ] );
			update_option( "wpts_{$key}", $value );
		}

		if ( class_exists( '\WPTS\Indexer\ScheduledSync' ) ) {
			\WPTS\Indexer\ScheduledSync::reschedule();
		}

		// Only flush search result cache if search-altering index or engine configurations changed.
		// UI/display layout settings (like results_layout, theme, debounce) should NEVER invalidate search cache.
		$needs_flush = false;
		$cache_altering_keys = [
			'search_engine',
			'post_types',
			'index_attachments',
			'index_woocommerce',
			'index_comments',
			'search_in_meta',
			'weight_title',
			'weight_excerpt',
			'weight_content',
			'weight_taxonomies',
			'weight_meta',
			'weight_comments',
			'enable_synonyms',
			'enable_spelling_suggestions',
			'typesense_host',
			'elasticsearch_host',
			'cache_driver',
			'cache_ttl',
		];

		foreach ( $cache_altering_keys as $ck ) {
			if ( array_key_exists( $ck, $data ) ) {
				$needs_flush = true;
				break;
			}
		}

		if ( $needs_flush && class_exists( '\WPTS\Core' ) ) {
			try {
				\WPTS\Core::instance()->get_engine()->flush_all();
			} catch ( \Throwable $e ) { // phpcs:ignore
			}
		}

		do_action( 'wpts_settings_saved', $data );
	}

	private static function cast( string $key, $value ) {
		$default = self::DEFAULTS[ $key ] ?? null;

		if ( is_array( $default ) ) {
			if ( ! is_array( $value ) ) {
				return ! empty( $value ) ? (array) $value : $default;
			}
			return $value;
		}

		if ( is_bool( $default ) ) {
			return (bool) $value;
		}

		if ( is_int( $default ) ) {
			return absint( $value );
		}

		return $value;
	}

	private static function sanitize( string $key, $value ) {
		switch ( $key ) {
			case 'post_types':
				return array_values( array_filter( array_map( 'sanitize_text_field', (array) $value ) ) );

			case 'role_restrictions':
				return is_array( $value ) ? $value : [];

			case 'debounce_ms':
			case 'results_per_page':
			case 'cache_ttl':
			case 'cdn_cache_ttl':
			case 'redis_port':
			case 'redis_db':
			case 'memcached_port':
			case 'tracking_retention_days':
			case 'weight_title':
			case 'weight_excerpt':
			case 'weight_content':
			case 'weight_taxonomies':
			case 'weight_meta':
			case 'weight_comments':
			case 'ab_debounce_b':
			case 'gdpr_anonymize_days':
			case 'admin_auto_refresh':
				return absint( $value );

			case 'typesense_port':
			case 'elasticsearch_port':
				return (string) absint( $value );

			case 'enable_frontend_search':
			case 'highlight_results':
			case 'search_in_meta':
			case 'index_thumbnails':
			case 'index_comments':
			case 'index_attachments':
			case 'index_woocommerce':
			case 'enable_spelling_suggestions':
			case 'enable_synonyms':
			case 'enable_boolean_search':
			case 'enable_phrase_search':
			case 'cache_stats':
			case 'cdn_cache_headers':
			case 'scheduled_reindex_enabled':
			case 'enable_voice_search':
			case 'enable_user_history':
			case 'enable_user_favorites':
			case 'enable_archive_live_filter':
			case 'tracking_enabled':
			case 'track_clicks':
			case 'ab_testing_enabled':
			case 'enable_honeypot':
			case 'enable_command_k_modal':
			case 'woocommerce_quick_add_to_cart':
			case 'enable_category_tabs_dropdown':
			case 'enable_vector_search':
			case 'multisite_cross_search':
				return ! empty( $value ) ? 1 : 0;

			case 'vector_weight':
				return (float) max( 0.0, min( 1.0, (float) $value ) );

			case 'vector_provider':
				return in_array( $value, [ 'openai', 'ollama', 'custom' ], true ) ? $value : 'openai';

			case 'cache_driver':
				return in_array( $value, [ 'none', 'auto', 'transient', 'wp_cache', 'memcached', 'redis' ], true ) ? $value : 'auto';

			case 'search_engine':
				return in_array( $value, [ 'mysql', 'typesense', 'elasticsearch' ], true ) ? $value : 'mysql';

			case 'results_layout':
				return in_array( $value, [ 'list', 'grid', 'card' ], true ) ? $value : 'list';

			case 'scheduled_reindex_interval':
				return in_array( $value, [ 'hourly', 'twicedaily', 'daily', 'weekly' ], true ) ? $value : 'daily';

			case 'scheduled_reindex_mode':
				return in_array( $value, [ 'incremental', 'full' ], true ) ? $value : 'incremental';

			case 'typesense_protocol':
			case 'elasticsearch_protocol':
				return in_array( $value, [ 'http', 'https' ], true ) ? $value : 'http';

			default:
				return sanitize_text_field( (string) $value );
		}
	}
}
