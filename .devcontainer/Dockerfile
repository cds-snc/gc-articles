FROM node:18-alpine@sha256:e0340f26173b41066d68e3fe9bfbdb6571ab3cad0a4272919a52e36f4ae56925 AS node

FROM wordpress:cli-php8.1@sha256:a8992c0d8c9df1f20cc0a1c86a2ca5c46f269b2380e353a0368052ff7bf73a16

COPY --from=node /usr/local/share /usr/local/share
COPY --from=node /usr/local/lib /usr/local/lib
COPY --from=node /usr/local/include /usr/local/include
COPY --from=node /usr/local/bin /usr/local/bin

WORKDIR /usr/src/wordpress

USER root

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN apk add --update --virtual mod-deps autoconf alpine-sdk \
    libmcrypt-dev && \
    # install dev utils
    apk add sudo --no-cache \
        nano \
        openssh \
        rsync \
        git \
        subversion \
        nodejs \
        npm \
        zsh \
        zsh-autosuggestions \
        zsh-syntax-highlighting \
        gettext && \
    # Install from v3.16 alpine community repo
    apk add --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/v3.16/community/ \
        php8-xmlwriter \
        php8-simplexml \
        php8-tokenizer \
        php8-dom \
        php8-xml

# Install Composer with this method instead of using `apk add composer` because
# the version of Composer in the Alpine package repository also installs a secondary version 
# of PHP that is not compatible with the version of PHP installed in the container.
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
     php composer-setup.php && \
     php -r "unlink('composer-setup.php');" && \
     mv composer.phar /usr/local/bin/composer;    

RUN pecl install pcov  \
    && docker-php-ext-enable pcov

# clean up
RUN apk del mod-deps && \
    rm -rf /apk /tmp/* /var/cache/apk/*

# Create non-root default user
ARG USER=default
ENV HOME=/home/$USER

RUN adduser -D $USER \
    && echo "$USER ALL=(ALL) NOPASSWD: ALL" > /etc/sudoers.d/$USER \
    && chmod 0440 /etc/sudoers.d/$USER

USER $USER
WORKDIR $HOME

# Oh-my-zsh
RUN sh -c "$(wget https://raw.github.com/robbyrussell/oh-my-zsh/master/tools/install.sh -O -)"
RUN echo "source /usr/share/zsh/plugins/zsh-syntax-highlighting/zsh-syntax-highlighting.zsh" >> ~/.zshrc && \
    echo "source /usr/share/zsh/plugins/zsh-autosuggestions/zsh-autosuggestions.zsh" >> ~/.zshrc && \
    echo "PROMPT=\"(devcontainer) \$PROMPT\"" >> ~/.zshrc

# A few helpful aliases
RUN echo "alias mods=\"cd ~/project/wordpress/wp-content/plugins/cds-wpml-mods\"" >> ~/.zshrc && \
    echo "alias gclists=\"cd ~/project/wordpress/wp-content/plugins/gc-lists\"" >> ~/.zshrc && \
    echo "alias cds=\"cd ~/project/wordpress/wp-content/plugins/cds-base\"" >> ~/.zshrc && \
    echo "alias webroot=\"cd /usr/src/wordpress\"" >> ~/.zshrc && \
    echo "alias wordpress=\"cd ~/project/wordpress\"" >> ~/.zshrc

ENV SHELL=/bin/zsh

CMD ["sleep", "infinity"]