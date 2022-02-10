# platform-mvp

## Demo

[GC Articles Live Demo](https://articles.cdssandbox.xyz/) (MVP)

## Local dev

Docker-compose and VS Code Remote Container environment featuring:

- apache (reverse-proxy to wordpress/php-fpm)
- mariadb (instead of mysql)
- wordpress (wordpress/php-fpm)
- wp-cli & composer (devcontainer)
- phpmyadmin (db admin)

## Requirements

Including installation instructions on a Mac.

- NPM (install [Node](https://nodejs.org/en/download/))
- Docker (install [Docker Desktop](https://www.docker.com/products/docker-desktop))
- Docker-compose (included with Docker Desktop)
- [VS Code](https://code.visualstudio.com/download) w/ Remote Containers extension (optional)
- [Composer](https://formulae.brew.sh/formula/composer)

## Config

Clone the repo and `cd` to the project directory.

```sh
git clone git@github.com:cds-snc/gc-articles.git
cd gc-articles
```

Copy the `.env.example` file to `.env` and customize as needed.

```sh
cp .env.example .env
```

First thing you'll want to add is an `ENCRYPTION_KEY` which will be used for encrypting sensitive information in the database such as the Notify API keys.

The encryption key is a base64 encoded random string. There is a helpful Composer command for generating a key:

```sh
composer generate-encryption-key
```

In your `.env` file, make sure to quote the entire string, including the "base64:" prefix.

```sh
ENCRYPTION_KEY="base64:dvss45WujgTWIy1lMspSU128PsnyV3fNXDfZNPZOG+k="
```

## Install things

The following to docker-compose tasks will get all your dependencies installed and database setup:

```sh
docker-compose run composer
docker-compose run install
```

Afterwards, download and build your frontend dependencies:

```sh
npm i
```

## Start it up

With your encryption key, environment configuration, and dependencies installed, you can bring up the environment using 
docker-compose from the root of the project.

```sh
docker-compose up
```

Once the services are running, you can access a cli environment preconfigured with all the tools needed for development:

```
npm run cli
```

Alternatively, you can open the project in VS Code Remote Containers:

- Open the project in VS Code
- When prompted "Reopen in container" or press F1 -> Remote-Containers: Open folder in container
- VS Code will open a devcontainer terminal environment

### Access WordPress

Either way, once the environment is up, the site will be available on `localhost`:

- Visit [`localhost`](http://localhost) to see your new WordPress install
- Visit [`localhost/login`](http://localhost/login) to see the admin interface

Wordpress will be installed with some pre-configured plugins and themes, and will be configured as a multi-site install. There will also be a default administrator account, with the following credentials:

username: `admin`
password: `secret`

## Useful services

### PHPMyAdmin

Web admin for MySQL database.

Web interface: `localhost:8080`

username: `dbuser`
password: `secret`

## Plugins and Themes

### Pre-installed plugins:

- [two-factor](https://wordpress.org/plugins/two-factor/)
- [wp-bootstrap-blocks](https://wordpress.org/plugins/wp-bootstrap-blocks/)
- [wp-native-php-sessions](https://en-ca.wordpress.org/plugins/wp-native-php-sessions/)
- [wps-hide-login](https://en-ca.wordpress.org/plugins/wps-hide-login/)
- [wpml](https://wpml.org/)
- [yoast](https://yoast.com/wordpress/plugins/seo/)
- [login-lockdown](https://en-ca.wordpress.org/plugins/login-lockdown/)
- [disable-user-login](https://en-ca.wordpress.org/plugins/disable-user-login/)


### Installing Plugins

This project is configured to use [Composer](https://getcomposer.org/) to manage [WordPress Themes and Plugins](https://www.smashingmagazine.com/2019/03/composer-wordpress/).

To install a plugin or theme, find it on [WPackagist](https://wpackagist.org/), add it to composer.json, and run `composer install` or use `composer require wpackagist-[plugin|theme]/[package-name]`. These commands should be run from within the `wordpress` folder.

Note: when starting up the devcontainer or docker-compose, `composer install` is run to automatically install plugins and themes defined in composer.json.

When adding plugins, if they should be automatically enabled for sites, make sure you activate them in:
- cypress/test-setup/setup-ci.sh
- docker-compose.yml

### Creating

When creating a custom plugin or theme, you should prefix the folder name with `cds-`. This will ensure the code is included in git and code quality scans.

### About plugin and theme dependencies and the vendor folder

Our custom plugin and theme both use Composer dependencies. It should be noted that they are configured to store their
dependencies in the wordpress-level vendor folder. This reduces the risk of conflicting vendor packages being loaded
by different autoloaders. 

See the `wordpress/composer.json` file for how we configure our plugin/theme as local "path" dependencies.

### About WPML

WPML is a paid/private plugin that doesn't support composer-based installs. As such, we we had to mirror the plugin 
code in our own [private plugin repository](https://github.com/cds-snc/sitepress-multilingual-cms). We will need to 
manually monitor for updates and update our private mirror, process TBD.

Once activated for the network, the plugin needs to be configured for each site. You can do this by visiting the WPML 
link in the sidebar. You will need an "activation key" which will require authorization to the vendor website.

Currently we are only using the WPML core plugin component, not the String Translation, Media Translation, or 
Translation Management components.

## Translations 
WordPress uses gettext to manage and compile translation files. We have added a couple composer scripts to
simplify working with the various commands and steps.

If you prefer to run these commands from your host environment, you'll need to make sure you have 
[composer](https://getcomposer.org/) and [gettext](https://formulae.brew.sh/formula/gettext) installed. 

Alternatively, if you work inside the `.devcontainer` or the `cli` container in the `docker-compose` environment,
all necessary dependencies are installed. You can enter the `cli` container in the `docker-compose` environment by 
running: `docker exec -it cli zsh`

Both the `cds-base` plugin and `cds-default` theme include these scripts and they work the same way. There are
also scripts in the base `wordpress`-folder level composer.json that will recursively call each of the theme and plugin
scripts.

### Translatable strings
When working with theme or plugin files, create translatable strings by following the 
[WordPress documentation](https://codex.wordpress.org/I18n_for_WordPress_Developers#Strings_for_Translation) on the
subject, making sure to set the `domain` as `cds-snc`. In short, you will probably use syntax like the following:

```
__( 'Hello, dear user!', 'cds-snc' );
```
or
```
_e( 'Your Ad here', 'cds-snc' );
```

In order to translate these strings and make them available to WordPress, you will go through the steps below.

_NOTE: You can execute the commands below in the `cds-base` plugin folder, the `cds-default` theme folder, or at 
the root `wordpress` folder, depending on scope/context._

1. Prepare translation files

```sh
composer prepare-translations
```

This will scan the plugin or theme files and update the `cds-snc.pot` file which captures all the translatable strings.
It will then merge any updates into the `fr_FR.po` file which is where translations are added.

2. Add translated strings

At this point, you should add any French translations to that .po file as described in the 
[WordPress documentation](https://developer.wordpress.org/plugins/internationalization/localization/#manually).

3. Compile translations

Once translatable strings have been merged to the .po file, and translations have been added, you need to compile the
translations into a format that WordPress can read with the second command.

```sh
composer compile-translations
```

At this point, you will likely have a bunch of local changes in the `languages` folder: 
- `cds-snc.pot`
- `fr_CA.mo`
- `fr_CA.po`
- one or more `fr_CA-xxx.json` files (* IF any strings appear in javascript files)

All of these files should be committed along with the rest of your PR.

## Tests

We are using Cypress to test workflows, [Pest](https://pestphp.com/) for PHP-language unit tests, and [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) for PHP-language linting.

### Cypress

To run the cypress tests, first spin up a wp-env environment. 

```
npm run wp-env:init
```

This brings up 2 wordpress environments: a dev environment on http://localhost:8888, and a test environment (used by cypress) on http://localhost:8889. It's not the same as what you see in the `docker compose up` local development environment, but it is close enough, and it allows us to test admin workflows. The default credentials for the wp-env environment are `admin` & `password`.

To open cypress in a browser, use

```
npm run cy:open
```

You can run test suites one at a time or the entire set of tests.

To reset the environment, to wipe out local changes, use

```
npm run wp-env:clean
```

When you are finished testing, you can spin down the wp-env environment with

```
npm run wp-env:stop
```

Additional commands can be found in the root-level `package.json` file.

### Pest

Pest is used for unit-testing PHP. Pest is a wrapper around PHPUnit that abstracting away a lot of the redundant/repetitive class structure code that you usually have with PHPUnit. Pest tests must be run from the `/wordpress` folder.

```
cd wordpress
./vendor/bin/pest
```

### PHP_CodeSniffer

PHP_CodeSniffer (`phpcs`) lints our PHP code to enforce a consistent style across the codebase. To run `phpcs`, first install it globally ([instructions for installing with Homebrew](https://gist.github.com/pbrocks/ab8d8c7ce200ce6f718181cebfc57a1e)). Then, you can lint the codebase with

```
phpcs .wordpress/wp-content/plugins/cds-base -n --standard=PSR12 --ignore=build,node_modules,vendor,tests,.css,.js
```

Note that this can pick up vendor files, so specifically targeting the module(s) you are working on (or individual files) will result in least amount of noise.

```
phpcs ./wordpress/wp-content/plugins/cds-base/classes/Modules/Cleanup/Articles.php --standard=PSR12
```

## Deployments

**NOTE** You will need to have [GitHub CLI](https://cli.github.com) installed to complete the following steps

1) Bump the version #

```bash
npm run update-version
```

> This should automatically update the [VERSION, theme and plugin files](https://github.com/cds-snc/platform-mvp-ircc/commit/d697a147499f36b2bff456d1be3d3a07e4e58711)

2) Visit Github and check the Pull Request that was created

<hr>

1) Create and tag a release

```bash
npm run tag-release
```

> This should automatically update the [terragrunt.hcl](https://github.com/cds-snc/platform-mvp-ircc/blob/a5ca0d5688ce2ce224cc846772c7fcdf2b615fdc/infrastructure/terragrunt/env/prod/ecs/terragrunt.hcl#L63) file

2) Visit Github and check the Pull Request that was created

**NOTE** This step will run a github cli command to create a release and tag on Github

The automated deployment will happen after your PR is merged.

> Important the tagged i.e. v1.x.x container needs to finish building before the tag release PR is merged.  You can check via the Github actions tab
