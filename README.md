# BuddyX: WordPress Community Theme

[![License: GPL](https://img.shields.io/github/license/vapvarun/buddyx)](/LICENSE)
[![GitHub release](https://img.shields.io/github/v/release/vapvarun/buddyx?include_prereleases)](https://github.com/vapvarun/buddyx/releases)
[![WordPress](https://img.shields.io/badge/WordPress-6.5%2B-blue)](https://wordpress.org)

## Your Community-Focused WordPress Theme

BuddyX is a powerful, flexible WordPress theme designed specifically for building online communities. Built with BuddyPress in mind, BuddyX provides everything you need to create engaging social platforms, membership sites, and collaborative online spaces.

Creating a theme with BuddyX means adopting modern web development practices and core principles built on:

- **Accessibility First** - WCAG compliant and screen reader friendly
- **Mobile-First Design** - Responsive across all devices
- **Progressive Enhancement** - Works for everyone, regardless of browser capabilities
- **Performance Optimized** - Fast loading and efficient
- **BuddyPress Ready** - Perfect for community features
- **WooCommerce Compatible** - E-commerce ready
- **Developer Friendly** - Clean code, well-documented

## Try BuddyX in your browser

[![Try in WordPress Playground](https://img.shields.io/badge/Try%20in-WordPress%20Playground-1E40AF?logo=wordpress&logoColor=white)](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/wbcomdesigns/buddyx/master/_playground/blueprint.json)

Launches a disposable WordPress site in your browser with the latest WordPress.org release of **BuddyX** and **BuddyPress** active, plus the theme's built-in **sample content** published automatically - 7 pattern-driven pages (Home, About, Services, Pricing, Journal, FAQ, Contact), a primary navigation menu, and a static front page. Nothing is installed on your machine and the site is discarded when you close the tab.

The Playground setup lives in [`_playground/blueprint.json`](_playground/blueprint.json) and pulls BuddyX from the WordPress.org theme directory, so it always reflects the current approved release. Sample content is created by the theme's own [`Starter_Content`](inc/Starter_Content/Component.php) component (BuddyX 5.1.0 or later). Run it locally with:

```bash
npx @wp-playground/cli@latest run-blueprint --blueprint=_playground/blueprint.json
```

## Key Features

### 🎨 **Design System**
- Modern, clean interface that works out of the box
- Multiple header layouts and navigation options
- Customizable color schemes and typography
- Widget-ready sidebars and footer areas
- Built-in block patterns and templates

### 👥 **Community Features**
- Full BuddyPress integration support
- Member profiles and activity streams
- Groups and forums compatibility
- Social networking features ready

### 🛒 **E-commerce Ready**
- WooCommerce compatibility
- Product display and shopping cart layouts
- Custom checkout and product page designs

### 🚀 **Performance**
- Optimized asset loading
- Lazy loading for images
- Minified CSS and JavaScript
- AMP support for mobile performance

### 🔧 **Developer Tools**
- Modern build system with ESBuild
- Component-based architecture
- 27 bundled block patterns, editable in the native block editor
- WP CLI commands for development
- Comprehensive testing suite

## Quick Start

### Requirements
- WordPress 6.5 or higher (tested up to 7.1)
- PHP 8.1 or higher
- Node.js 20.13.1 or higher (for development)
- npm 10.5.2 or higher (for development)

### Installation

1. **Download the latest release** from [Releases](https://github.com/vapvarun/buddyx/releases/latest), or install BuddyX straight from **Appearance > Themes > Add New** inside WordPress. Release assets are versioned, e.g. `buddyx-5.1.9.zip`.

2. **Install via WordPress admin:**
   - Navigate to Appearance > Themes > Add New > Upload Theme
   - Upload the downloaded zip
   - Activate the theme

3. **Or install manually:**
   ```bash
   cd wp-content/themes/
   unzip buddyx-5.1.9.zip
   # Activate in WordPress admin
   ```

### Development Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/vapvarun/buddyx.git
   cd buddyx
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Start development:**
   ```bash
   npm run dev
   ```

## Development Commands

```bash
# Start development server
npm run dev

# Build for production
npm run build

# Build CSS or JS on their own
npm run build:css
npm run build:js

# Run linting (JS and CSS)
npm run lint

# Run the Jest unit tests
npm run test:js

# Run the Playwright end-to-end tests
npm run test:e2e

# Package a release zip (runs the browser-smoke release gate first)
npm run dist
```

`npm run bundle` builds the same zip but skips the release gate, so use `npm run dist` for anything customer-facing.

## Configuration

BuddyX can be configured through:

- **WordPress Customizer** - Real-time visual customization
- **Build configuration** - `config/config.default.json`, overridden per machine by an untracked `config/config.json`

### config/config.default.json:

```json
{
  "theme": {
    "slug": "buddyx",
    "name": "BuddyX",
    "author": "wbcomdesigns,vapvarun",
    "version": "5.1.9"
  },
  "dev": {
    "browserSync": { }
  }
}
```

## Customization

### Child Themes

Use a child theme to customize BuddyX safely:

- Download the child theme: https://github.com/wbcomdesigns/buddyx-child/releases/latest
- More on child themes: https://developer.wordpress.org/themes/advanced-topics/child-themes/

### CSS Variables
Override theme styles using CSS custom properties:

```css
:root {
  --buddyx-primary-color: #your-color;
  --buddyx-font-family: 'Your Font', sans-serif;
}
```

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Android Chrome)

## The Wbcom Designs stack

BuddyX is built by [Wbcom Designs](https://wbcomdesigns.com/). Every plugin below is self-hosted on your own WordPress site, with a free core and an optional Pro layer. No per-seat pricing, no per-ticket cut, no platform fee.

| Product | What it does | Links |
|---|---|---|
| **BuddyNext** | A complete community platform in one free plugin: activity feed, spaces, member profiles, direct messaging, a member directory, and a full moderation suite. | [Site](https://buddynext.com/) · [Download](https://wbcomdesigns.com/downloads/buddynext/) |
| **Jetonomy** | Forums, Q&A, and ideas boards on their own database tables, so search and threading stay fast past 100,000 topics. | [Site](https://jetonomy.org/) · [Download](https://wbcomdesigns.com/downloads/jetonomy/) |
| **Learnomy** | The simplest LMS for WordPress: build courses, sell them with built-in Stripe and PayPal, run quizzes, issue signed certificates, and pay instructors. | [Site](https://learnomy.app/) · [Download](https://wbcomdesigns.com/downloads/learnomy/) |
| **Eventonomy** | Events, RSVPs, ticketing, and calendars on your own site, with no per-ticket platform fees. | [Site](https://eventonomy.org/) · [Download](https://wbcomdesigns.com/downloads/eventonomy/) |
| **Listora** | A directory that scales past 100,000 listings: faceted search, frontend submission, business claims, anti-spam, and Gutenberg blocks. | [Site](https://listora.org/) · [Download](https://wbcomdesigns.com/downloads/listora/) |
| **WP Career Board** | A job board with live search, employer and candidate dashboards, applications, company profiles, and a Pro ATS hiring pipeline. | [Site](https://wpcareerboard.com/) · [Download](https://wbcomdesigns.com/downloads/wp-career-board/) |

### BuddyX Pro

**BuddyX Pro** builds on this theme with dark-mode colour controls (47 `dark_*` customizer keys against free's toggle alone), a login modal and login-page designer, scroll-to-top, a side panel, a topbar, marketplace support (Dokan, WCFM, MultiVendorX), dedicated LMS layout controls, AMP and PWA, and 301 customizer settings against free's 130. Released in lockstep with this theme at matching versions.

- **Product page:** https://wbcomdesigns.com/downloads/buddyx-pro-theme/
- **Changelog:** https://wbcomdesigns.com/release-notes/buddyx-pro-theme/

## Contributing

We welcome contributions! Please see our [contributing guidelines](CONTRIBUTING.md) before submitting pull requests.

### Development Workflow
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests: `npm run test:js`
5. Submit a pull request

## Support

- **Documentation**: [buddyxtheme.com/docs](https://buddyxtheme.com/docs/)
- **Issues**: [GitHub Issues](https://github.com/vapvarun/buddyx/issues)
- **Support forum**: [wordpress.org/support/theme/buddyx](https://wordpress.org/support/theme/buddyx/)
- **Updates**: Follow [@vapvarun](https://github.com/vapvarun)

## Credits

- **Lead Developers**: [wbcomdesigns](https://github.com/wbcomdesigns), [vapvarun](https://github.com/vapvarun)
- **Contributors**: [All Contributors](https://github.com/vapvarun/buddyx/graphs/contributors)
- **Inspiration**: Based on modern WordPress development best practices

## License

BuddyX is licensed under the [GNU General Public License v3.0](https://www.gnu.org/licenses/gpl-3.0.html).

---

*Note: Originally based on BuddyX architecture but completely reimagined for community and BuddyPress integration.*