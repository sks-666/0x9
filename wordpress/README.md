# WordPress Custom Theme & Plugin Scaffold

A bare-bones starting point for custom WordPress development. This is **not**
a full WordPress installation — it assumes you already have (or will set up)
a WordPress core install (via [LocalWP](https://localwp.com/), Docker,
XAMPP, or a host), and just drops a custom theme and plugin into it.

## Structure

```
wordpress/
├── themes/
│   └── starter-theme/     # Custom theme scaffold
│       ├── style.css      # Theme header + base styles
│       ├── functions.php  # Theme setup, enqueue, nav menu, sidebar
│       ├── header.php
│       ├── footer.php
│       ├── index.php      # Blog/archive loop
│       ├── single.php     # Single post template
│       └── page.php       # Static page template
└── plugins/
    └── starter-plugin/    # Custom plugin scaffold
        ├── starter-plugin.php
        └── readme.txt
```

## Requirements

- WordPress >= 6.0
- PHP >= 7.4

## Getting started

1. Get a local WordPress instance running (pick one):
   - [LocalWP](https://localwp.com/) — easiest GUI option.
   - `wp-cli` + PHP built-in server: `wp core download && wp config create --dbname=... && wp core install ...`
   - Docker: `docker run` an `official WordPress + MySQL image, or write your own `docker-compose.yml`.
2. Symlink (or copy) the theme and plugin into your WordPress install:

   ```bash
   ln -s "$(pwd)/wordpress/themes/starter-theme" /path/to/wordpress/wp-content/themes/starter-theme
   ln -s "$(pwd)/wordpress/plugins/starter-plugin" /path/to/wordpress/wp-content/plugins/starter-plugin
   ```

3. Activate both from **wp-admin → Appearance/Plugins**, or via WP-CLI:

   ```bash
   wp theme activate starter-theme
   wp plugin activate starter-plugin
   ```

4. Start building — edit templates under `themes/starter-theme/` and add
   functionality to `plugins/starter-plugin/starter-plugin.php`.

## Notes

- The theme uses `add_theme_support` for title-tag, post-thumbnails, HTML5
  markup, and a custom logo, and registers a `primary` nav menu location and
  a `Sidebar` widget area.
- The plugin registers a sample `[starter_plugin_hello]` shortcode as a
  reference for adding your own hooks/shortcodes/blocks.
- No WordPress core files are checked into this repo — only the custom
  theme/plugin code that layers on top of a core install.
