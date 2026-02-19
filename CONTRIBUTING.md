# Contributing to BuddyX

BuddyX is an open source theme built for WordPress communities and social networks. Contributors and contributions are welcome!

If you have found a problem or want to suggest an improvement or new feature, please file an issue being careful to follow the provided template.

If you want to contribute code to BuddyX, please follow the instructions below.

## Office Hours

BuddyX office hours are available through our community channels. Please check our [GitHub Discussions](https://github.com/wbcomdesigns/buddyx/discussions) for current schedules.

## Development Workflow

1. **Set up local development environment**
   ```bash
   git clone https://github.com/wbcomdesigns/buddyx.git
   cd buddyx
   composer install
   npm install
   npm run dev
   ```

2. **Fork the repository**
   - Visit https://github.com/wbcomdesigns/buddyx
   - Click "Fork" button

3. **Create a feature branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

4. **Make your changes**
   - Write clean, well-documented code
   - Follow WordPress coding standards
   - Test your changes thoroughly
   - Update documentation if needed

5. **Test your changes**
   ```bash
   npm test
   npm run lint
   ```

6. **Commit your changes**
   ```bash
   git add .
   git commit -m "feat: Add your feature description"
   ```

7. **Push to your fork**
   ```bash
   git push origin feature/your-feature-name
   ```

8. **Submit a Pull Request**
   - Visit your fork on GitHub
   - Click "New Pull Request"
   - Choose the `wbcomdesigns/buddyx` repository as the base
   - Fill out the PR template with detailed information

## Guidelines for Pull Requests

### Code Standards
- Follow [WordPress Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/)
- Use [ESLint](https://eslint.org/) configuration provided
- Write [PHPDoc](https://docs.phpdoc.org/) comments for all functions and classes
- Ensure accessibility compliance (WCAG 2.1)

### Testing
- All new features must include tests
- Run `npm test` before submitting
- Test on multiple browsers when applicable
- Verify BuddyPress, WooCommerce, and other integrations work properly

### Documentation
- Update inline documentation for code changes
- Update README.md if adding new user-facing features
- Add comments for complex logic

### Performance
- Optimize for page speed and Core Web Vitals
- Use lazy loading for images and scripts where appropriate
- Ensure responsive design works across all device sizes

### Security
- Follow WordPress security best practices
- Sanitize all user inputs
- Use WordPress nonce verification for forms
- Escape all output properly

## Bug Reports

When filing bug reports, please include:

- **WordPress version** you're using
- **PHP version** 
- **Browser and version** where the issue occurs
- **Steps to reproduce** the issue
- **Expected behavior** vs **actual behavior**
- **Screenshots** if applicable
- **Error messages** from browser console or WordPress debug log

## Feature Requests

For feature requests, please provide:

- **Use case** - What problem does this solve?
- **Proposed solution** - How should it work?
- **User impact** - Who benefits from this feature?
- **Alternatives considered** - Have you looked at other approaches?

## Community Guidelines

- Be respectful and constructive in all interactions
- Help others who are asking questions
- Welcome newcomers and help them get started
- Focus on what's best for the community and theme users

## Getting Help

- **GitHub Issues**: [Create a new issue](https://github.com/wbcomdesigns/buddyx/issues/new)
- **GitHub Discussions**: [Ask questions](https://github.com/wbcomdesigns/buddyx/discussions/new)
- **Documentation**: Check the [wiki](https://github.com/wbcomdesigns/buddyx/wiki)

## Recognition

Contributors will be recognized in:
- [Contributors list](https://github.com/wbcomdesigns/buddyx/graphs/contributors)
- Release notes
- Theme documentation

Thank you for contributing to BuddyX! 🎉