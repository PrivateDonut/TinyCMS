# DonutCMS

**DonutCMS** (formerly TinyCMS) is a versatile, secure, and user-friendly content management system designed specifically for World of Warcraft Emulation. It simplifies the management of WoW emulator servers and provides a rich set of features for both administrators and users. DonutCMS supports multiple emulators, making it a flexible choice for a variety of server configurations.

## Latest Features

### Enhanced Security

- **XSS Protection**: Implemented robust cross-site scripting (XSS) protection using the voku/anti-xss library.
- **CSRF Protection**: Added Cross-Site Request Forgery (CSRF) protection for all forms.
- **Secure Password Handling**: Improved password hashing and storage mechanisms.

### User Experience Improvements

- **Responsive Design**: Fully responsive front-end for seamless experience across devices.
- **Customizable Themes**: Easy-to-use theming system for personalized server branding.

### Front-End Features

- **User Authentication**: Secure user registration and login system.
- **News System**: Integrated news posts to keep your community updated.
- **Account Panel**: User-friendly interface for account management.
- **Character Management**: View and manage in-game characters.
- **Shop** (Work in Progress): Integrated shop for in-game purchases and donations.

### Admin Panel Features

- **Account Management**: Comprehensive tools for managing user accounts.
- **Server Statistics**: Detailed metrics and performance monitoring.
- **Content Management**: Easy-to-use interface for managing news and site content.
- **Log Viewer**: Access and analyze server logs directly from the admin panel.

## Screenshots

![DonutCMS home page](https://i.imgur.com/DjEFs2o.png)

## Getting Started

Setting up DonutCMS is quick and easy:

1. Download the latest release from the [releases page](https://github.com/PrivateDonut/DonutCMS/releases).
2. Place the DonutCMS files inside your web server's root directory.
3. Navigate to your website's URL in a web browser.
4. The site will automatically redirect you to the installation page on first load.
5. Follow the on-screen instructions to complete the setup of DonutCMS.

## System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache, Nginx, etc.)

## Configuration

After installation, you can configure DonutCMS by editing the `db_config.php` file located in the `/engine/configs/` directory. Here you can set database credentials, site settings, and more.

## Plugin System

DonutCMS now features a robust plugin system, allowing for easy extension of functionality:

- **Easy Integration**: Seamlessly integrate new features into your CMS.
- **Community Plugins**: Access a growing library of community-developed plugins.
- **Custom Development**: Create your own plugins to tailor DonutCMS to your specific needs.

## Contributing

We welcome contributions to DonutCMS! If you'd like to contribute, please fork the repository and create a pull request with your changes.

## Support

If you encounter any issues or have questions, please [open an issue](https://github.com/PrivateDonut/DonutCMS/issues) on our GitHub repository.

## License

DonutCMS is open-source software licensed under the [GNU General Public License v3.0](https://github.com/PrivateDonut/DonutCMS/blob/main/LICENSE).

## Acknowledgements

DonutCMS is built with love by the WoW emulation community. Special thanks to all contributors and supporters!

## Roadmap

- Implement website admin panel
- New admin panel design
- New default template
- Implement shop for in-game purchases
- Implement payment gateway
- Improve caching mechanisms for better performance
- Implement multi-language support

Stay tuned for more exciting features and improvements!
