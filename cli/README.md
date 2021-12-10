# Articles CLI (ALPHA)

A few helpful commands to get things running.

To get started, run this command once:

```sh
./cli/bin/articles install
```

This will start up a few docker services and install some dependencies.

Now, by default the cli is available at `./vendor/bin/articles` but we recommend adding an alias in your .zshrc file:

```sh
alias articles='[ -f articles ] && bash articles || bash vendor/bin/articles'
```

Now you can run all of the following commands:

- `articles install`: Install composer dependencies at root
- `articles update`: Update composer dependencies at root
- `articles wordpress install`: Install WordPress composer dependencies
- `articles wordpress update`: Update WordPress compose dependencies
- `articles up`: Bring up the full docker-compose environment
- `articles down`: Bring down the full docker-compose environment
- `articles npm install`: Install and build npm dependencies