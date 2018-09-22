FROM debian:jessie

MAINTAINER Alireza Rahmani Khalili "alirezarahmani@live.com"

ENV TERM xterm

RUN apt-get update --fix-missing && apt-get install -y --force-yes curl sudo vim
RUN apt-get install -y --force-yes wget apt-transport-https lsb-release ca-certificates
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg

RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list
RUN echo "deb http://ftp.uk.debian.org/debian jessie-backports main" >> /etc/apt/sources.list

RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list

RUN DEBIAN_FRONTEND="noninteractive" apt-get update && apt-get install -y --force-yes \
    nginx \
    php7.2 \
    php7.2-cli \
    php7.2-pdo \
    php7.2-curl \
    php7.2-json \
    php7.2-mysql \
    php7.2-xml \
    php7.2-mbstring \
    php-memcached

RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && chmod +x /usr/local/bin/composer
WORKDIR /var/www/
CMD ["nginx", "-g", "daemon off;"]