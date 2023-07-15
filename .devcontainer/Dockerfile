FROM node:16-alpine@sha256:6c381d5dc2a11dcdb693f0301e8587e43f440c90cdb8933eaaaabb905d44cdb9 AS node

FROM wordpress:cli-php8.1@sha256:93b75d8a87a79e0e57b0d7d3553fc0bc99e4786222a201a31223bbbe8343770b

COPY --from=node /usr/lib /usr/lib
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
    # Install from testing/edge repository
    apk add --no-cache --repository=http://dl-cdn.alpinelinux.org/alpine/edge/testing/ \
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
ENV HOME /home/$USER

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

ENV SHELL /bin/zsh

CMD ["sleep", "infinity"]