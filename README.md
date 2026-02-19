<img align="right" width="90" height="90"
src="https://avatars.githubusercontent.com/u/38340689"
title="BuddyX logo by Wbcom Designs">

# BuddyX: WordPress Community Theme

[![Build Status](https://github.com/vapvarun/buddyx/workflows/CI/badge.svg)](https://github.com/vapvarun/buddyx/actions)
[![License: GPL](https://img.shields.io/github/license/vapvarun/buddyx)](/LICENSE)
[![GitHub release](https://img.shields.io/github/v/release/vapvarun/buddyx?include_prereleases)](https://github.com/vapvarun/buddyx/releases)

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
- Custom block development support
- WP CLI commands for development
- Comprehensive testing suite

## Quick Start

### Requirements
- WordPress 5.4 or higher
- PHP 8.0 or higher
- Node.js 20.13.1 or higher (for development)
- npm 10.5.2 or higher (for development)

### Installation

1. **Download the latest release:**
   ```bash
   wget https://github.com/vapvarun/buddyx/releases/latest/download/buddyx.zip
   ```

2. **Install via WordPress admin:**
   - Navigate to Appearance > Themes > Add New
   - Upload the buddyx.zip file
   - Activate the theme

3. **Or install manually:**
   ```bash
   cd wp-content/themes/
   unzip buddyx.zip
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

# Run linting
npm run lint

# Run tests
npm test

# Create new block
npm run block:create my-block

# Remove block
npm run block:remove my-block

# Convert to child theme
npm run theme:child
```

## Configuration

BuddyX can be configured through:

- **WordPress Customizer** - Real-time visual customization
- **Theme Options Panel** - Advanced configuration options
- **Theme Configuration File** - `config/themeConfig.js` for developers

### Example themeConfig.js:

```javascript
module.exports = {
  theme: {
    slug: 'buddyx',
    name: 'BuddyX',
    author: 'wbcomdesigns,vapvarun',
    // ... other options
  },
  dev: {
    proxyURL: 'localbuddyx.test',
    // ... development options
  }
};
```

## Customization

### Child Themes
Create a child theme to safely customize BuddyX:

```bash
npm run theme:child my-buddyx-child
```

### Custom Blocks
Develop custom blocks with the included scaffolding:

```bash
npm run block:create my-custom-block
```

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

## Contributing

We welcome contributions! Please see our [contributing guidelines](CONTRIBUTING.md) before submitting pull requests.

### Development Workflow
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests: `npm test`
5. Submit a pull request

## Support

- **Documentation**: [BuddyX Documentation](https://github.com/vapvarun/buddyx/wiki)
- **Issues**: [GitHub Issues](https://github.com/vapvarun/buddyx/issues)
- **Discussions**: [Community Forum](https://github.com/vapvarun/buddyx/discussions)
- **Updates**: Follow [@vapvarun](https://github.com/vapvarun)

## Credits

- **Lead Developers**: [wbcomdesigns](https://github.com/wbcomdesigns), [vapvarun](https://github.com/vapvarun)
- **Contributors**: [All Contributors](https://github.com/vapvarun/buddyx/graphs/contributors)
- **Inspiration**: Based on modern WordPress development best practices

## License

BuddyX is licensed under the [GNU General Public License v3.0](https://www.gnu.org/licenses/gpl-3.0.html).

---

*Note: Originally based on BuddyX architecture but completely reimagined for community and BuddyPress integration.*