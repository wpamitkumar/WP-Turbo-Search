=== Turbo Search ===
Contributors: wpamitkumar
Donate link: https://profiles.wordpress.org/wpamitkumar
Tags: search, live search, ajax search, woocommerce search, typesense, Elasticsearch, PDF search, AI vector search, cache
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Enterprise-grade instant search engine with sub-10ms queries, full-text PDF/DOCX extraction, AI vector embeddings, Typesense & Redis.

== Description ==

**Turbo Search** is a modern, high-performance search and discovery engine for WordPress and WooCommerce. Built for speed and scale, it replaces default slow database queries with an indexed full-text engine, sub-10ms query execution, and optional hybrid AI vector reranking.

### ⚡ Key Features

* **Instant Live Search**: Sub-10ms query execution with real-time dropdown and keyboard navigation.
* **3 Results Layout Modes**: Choose between List View, responsive Grid Cards, and dense Compact Card layout.
* **Multi-Tier Fallback Architecture**: Automatic failover from Typesense / Elasticsearch to local MySQL and native WordPress Core `WP_Query`.
* **Document & Attachment Extraction**: Native full-text parsing for `.pdf`, `.docx`, `.txt`, `.csv`, `.tsv`, and `.md` files without heavy external server dependencies.
* **Pluggable Search Backends**: Works out-of-the-box on MySQL/MariaDB FULLTEXT, with 1-click drivers for Typesense Server and Elasticsearch / OpenSearch clusters.
* **Hybrid Semantic AI Vector Search**: Combines keyword fulltext with OpenAI or local self-hosted Ollama embeddings (`nomic-embed-text`) using Reciprocal Rank Fusion (RRF).
* **Multi-Tier Caching**: In-memory JavaScript query caching, HTTP ETag headers, Redis, Memcached, and WordPress Transients.
* **Command+K Spotlight Modal**: Global keyboard shortcut modal (`⌘K` / `Ctrl+K`) for macOS and Windows.
* **Voice Search**: Built-in Web Speech API speech-to-text recognition (works with HTTPS connection only).
* **WooCommerce Integration**: 1-click AJAX Quick Cart, SKU search, regular/sale price schedules, and stock filters.
* **Faceted Category Multi-Tabs**: Instant tabbed filtering by post type, product category, or custom taxonomy.
* **Analytics & CTR Telemetry**: Track top search queries, zero-result searches, and click-through-rate ranking positions with GDPR-compliant IP anonymization.
* **Enterprise Multisite & Multilingual**: Cross-network multisite search with subsite origin badging, plus native WPML and Polylang integration.
* **DevOps Ready**: Unified `wp turbo-search` WP-CLI command suite for reindexing, cache management, and automated cron jobs.
* **100% Private & Self-Contained**: No external third-party CDN leaks; all dependencies and assets are bundled locally.

== Installation ==

1. Upload `turbo-search` to the `/wp-content/plugins/` directory, or install the ZIP file via **Plugins → Add New → Upload Plugin**.
2. Activate the plugin through the **Plugins** screen in WordPress.
3. Go to **Turbo Search → Index Manager** and click **Re-index All Posts** to populate the search index table.
4. Add the search bar to your site using the **Turbo Search Bar** Gutenberg Block or the shortcode `[wpts_search]`.

== Frequently Asked Questions ==

= Does Turbo Search require external server software like Typesense or Elasticsearch? =
No. Turbo Search includes a native MySQL / MariaDB FULLTEXT engine that works on any standard shared hosting, VPS, or dedicated server out of the box with zero external dependencies. External drivers (Typesense, Elasticsearch, Redis) are optional for massive scale.

= How does PDF and document search work? =
When files are uploaded to the WordPress Media Library or attached to posts/products, Turbo Search parses text content directly using native PHP compression and XML parsers.

= Is search tracking GDPR compliant? =
Yes. IP addresses and user agents are hashed or anonymized according to the configured retention schedule (default 30 days).

= Can I customize search result templates? =
Yes. You can override templates in your child theme or use the extensive developer hooks API (e.g. `wpts_result_hit`, `wpts_indexable_document`).

= Does Voice Search work on plain HTTP connections? =
No. Modern web browsers (Google Chrome, Apple Safari, Microsoft Edge) enforce W3C device security policies and require an HTTPS connection (or localhost for local development) to access the microphone.

== Screenshots ==

1. Live instant search dropdown with highlighted keywords and WooCommerce Quick Buy button.
2. Spotlight Command+K modal overlay with recent history and category tabs.
3. Interactive Admin Dashboard with live search volume, CTR rankings, and zero-result search analytics.
4. Modular Search Engine configuration panel (MySQL, Typesense, Elasticsearch).
5. Hybrid AI Vector Search configuration with OpenAI and local Ollama support.

== Changelog ==

= 1.0.0 =
* Initial release of Turbo Search.
* Event-driven incremental indexing engine (0ms post update delay).
* Native full-text document parsing for PDF, DOCX, TXT, CSV, TSV, and MD attachments with continuous word tokenization.
* 3 Visual Results Layouts: List View, Grid Cards, and Compact Card.
* Multi-Tier search engine & caching fallback with graceful WordPress Core native `WP_Query` failover.
* Multi-Tier caching engine (Redis, Memcached, Transients, Edge CDN headers).
* Hybrid Semantic AI Vector Search (OpenAI, Ollama, Custom HTTP).
* Unified `wp turbo-search` WP-CLI command suite (12 subcommands).
* Built-in Analytics, CTR tracking, and A/B testing suite.
* WordPress Multisite cross-network and WPML/Polylang multilingual compatibility.
* 100% self-contained codebase without third-party CDN dependencies.

== Upgrade Notice ==

= 1.0.0 =
Initial production release of Turbo Search.


