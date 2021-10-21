FROM wordpress:cli-php8.0

USER root

RUN apk add --update --virtual mod-deps autoconf alpine-sdk \
    libmcrypt-dev && \
    # install dev utils
    apk add sudo --no-cache \
        nano \
        openssh \
        rsync \
        git \
        composer \
        nodejs \
        npm \
        zsh \
        zsh-autosuggestions \
        zsh-syntax-highlighting \
        gettext

RUN pecl install pcov \
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

ENV SHELL /bin/zsh

CMD ["sleep", "infinity"]