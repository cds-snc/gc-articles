# Articles CLI (ALPHA)

A few helpful commands to get things running.

To get started, run this command once:

```sh
./cli/bin/articles init
```

This will start up a few docker services and install some dependencies.

Now, by default the cli is available at `./vendor/bin/articles` but we recommend 
adding an alias in your .zshrc or .bash_profile file:

```sh
alias articles='[ -f articles ] && bash articles || bash vendor/bin/articles'
```

Now you can run the following commands:

- `articles init`: Prepare or update this CLI
- `articles generate-encryption-key`: Generate Encryption Key
- `articles install`: Install WordPress composer dependencies
- `articles update`: Update WordPress compose dependencies
- `articles wordpress install`: Run wp-cli installer to setup the database, theme, and plugins
- `articles up`: Bring up the full docker-compose environment
- `articles down`: Bring down the full docker-compose environment
- `articles npm install`: Install and build npm dependencies
- `articles db-cleanup`: Lists up any orphaned multisite tables
- `articles db-cleanup delete`: Delete any orphaned multisite tables

## Starting fresh

A typical onboarding might look like:

- Clone this repo
- Configure .env vars (except ENCRYPTION_KEY)
- Run `./cli/bin/articles init`
- Create an alias (see above - you only need to do this once if you put it in your .zshrc or .bash_profile)
- Run `articles generate-encryption-key` and copy that key into your .env file
- Run `articles install` to install WordPress dependencies
- Run `articles npm install` to install and build npm and javascript dependencies
- Run `articles wordpress install` to setup your database
- Run `articles up` to bring up the rest of the environment
- Visit `localhost` and profit!