FROM wordpress:cli-php8.0

# ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
USER root

# install utils
RUN apk add sudo --no-cache \
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