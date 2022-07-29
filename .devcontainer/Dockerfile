FROM wordpress:cli-php8.1

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
    composer \
    nodejs \
    npm \
    zsh \
    zsh-autosuggestions \
    zsh-syntax-highlighting \
    gettext \
    php8-xmlwriter \
    php8-simplexml \
    php8-tokenizer \
    php8-dom \
    php8-xml

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