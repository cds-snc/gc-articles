# platform-mvp

## Local dev

Docker-compose and VS Code Remote Container environment featuring:

- mysql5.7
- wordpress (wordpress/apache)
- wp-cli & composer (devcontainer)
- mailhog (fake mail)
- phpmyadmin (db admin)

## Requirements

- NPM
- Docker
- Docker-compose
- VS Code w/ Remote Containers extension (optional)

## Config

Copy the .env.example file to .env and customize as necessary (you might not need to change anything)

```
cp .env.example .env
```

## Usage

To start, clone the repo and then run NPM install:

```
git clone git@github.com:cds-snc/platform-mvp.git
cd platform-mvp
npm i
```

Then you can bring up the environment using docker-compose:

```
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

- Visit `localhost` to see your new WordPress install
- Visit `localhost/wp-admin` to see the admin interface

Wordpress will be installed with some pre-configured plugins and themes, and will be configured as a multi-site install. There will also be a default administrator account, with the following credentials:

username: admin
password: secret

## Useful services

### Mailhog

Local mailcatcher for fake email sending. To use it, configure your WordPress install to use the SMTP interface.

Web interface: `localhost:8025`
SMTP interface: `mailhog:1025`

### PHPMyAdmin

Web admin for MySQL database.

Web interface: `localhost:8080`

## Plugins and Themes

Pre-installed plugins:

- [two-factor](https://wordpress.org/plugins/two-factor/)

### Installing

This project is configured to use [Composer](https://getcomposer.org/) to manage [WordPress Themes and Plugins](https://www.smashingmagazine.com/2019/03/composer-wordpress/).

To install a plugin or theme, find it on [WPackagist](https://wpackagist.org/), add it to composer.json, and run `composer install` or use `composer require wpackagist-[plugin|theme]/[package-name]`. These commands should be run from within the `wordpress` folder.

Note: when starting up the devcontainer or docker-compose, `composer install` is run to automatically install plugins and themes defined in composer.json.

### Creating

When creating a custom plugin or theme, you should prefix the folder name with `cds-`. This will ensure the code is included in git and code quality scans.

### Theme translation 
See: https://github.com/cds-snc/platform-mvp/pull/95#issuecomment-904688973


### Deployments 

1) Bump the version #

```bash
node  ./scripts/bump-release.js --version_num 1.0.X
```

> This should automatcially update the [VERSION, theme and plugin files](https://github.com/cds-snc/platform-mvp-ircc/commit/d697a147499f36b2bff456d1be3d3a07e4e58711)

2) Create a PR, merge ...

3) Create a Github release tagged as the vesion number i.e. [v1.0.6](https://github.com/cds-snc/platform-mvp-ircc/releases/tag/v1.0.6)


3) Update the deployment docker image `version_tag`

```bash
node  ./scripts/bump-release.js --version_tag v1.0.X
```

> This should automatically update the [terragrunt.hcl](https://github.com/cds-snc/platform-mvp-ircc/blob/a5ca0d5688ce2ce224cc846772c7fcdf2b615fdc/infrastructure/terragrunt/env/prod/ecs/terragrunt.hcl#L63) file  

4) Create a PR, merge ...

The automated deployment will happen after your PR is merged
