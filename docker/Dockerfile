FROM ubuntu:xenial
MAINTAINER Michael Hutchings <michael.hutchings@mvfglobal.com>

RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get -yq dist-upgrade \
    && DEBIAN_FRONTEND=noninteractive apt-get -yq --no-install-recommends install \
        software-properties-common \
    && LANG=C.UTF-8 add-apt-repository -y ppa:ondrej/php \
    && DEBIAN_FRONTEND=noninteractive apt-get -yq purge \
        software-properties-common \
    && DEBIAN_FRONTEND=noninteractive apt-get -yq autoremove \
    && rm -rf /var/lib/apt/lists/*

RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get -yq --no-install-recommends install \
        apache2 \
        libapache2-mod-php7.1 \
        php7.1 \
        php7.1-cli \
        php7.1-curl\
        php7.1-json \
        php7.1-mbstring \
        php7.1-mysql\
        php7.1-xml \
        curl \
        unzip \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sL https://deb.nodesource.com/setup_8.x -o nodesource_setup.sh
RUN apt-get update
RUN bash nodesource_setup.sh
RUN apt-get install -yq nodejs

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

COPY docker/apache2.conf /etc/apache2/apache2.conf
COPY docker/default.conf /etc/apache2/sites-available/default.conf
RUN a2enmod rewrite \
    && a2dissite default \
    && a2ensite default \
    && a2dissite 000-default \
    && ln -sf /dev/stdout /var/log/apache2/access.log \
    && ln -sf /dev/stderr /var/log/apache2/error.log \
    && rm -rf /var/www/html \
    && mkdir -p /app \
    && ln -s /app /var/www/html

COPY docker/web.sh /run-web.sh
RUN chmod 755 /run-*.sh

COPY app /app
RUN chown -R www-data:www-data /app

EXPOSE 80
WORKDIR /app
CMD ["/run-web.sh"]
