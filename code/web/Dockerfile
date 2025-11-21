FROM php:8-apache@sha256:c7e5eb67e3f8a2fa92033cdce54209ac5ff16942ed398f22681a4ea83c9020f1

RUN apt-get update && \
    apt-get install -y --no-install-recommends vim git wget rsync default-mysql-client libpng-dev icu-devtools libicu-dev libjpeg-dev graphicsmagick-libmagick-dev-compat libzip-dev sudo less curl ldap-utils netcat-traditional iproute2 gnupg libldap2-dev

RUN apt-get update && apt-get install -y iputils-ping dnsutils zip # For web tools

RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    libcurl4-openssl-dev \
    libsasl2-dev \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && apt-get clean

ENV DEBIAN_FRONTEND=noninteractive

RUN docker-php-ext-configure bcmath
RUN docker-php-ext-configure exif
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-configure intl
RUN docker-php-ext-configure pdo_mysql 
RUN docker-php-ext-configure mysqli
RUN docker-php-ext-configure zip
RUN	docker-php-ext-install -j "$(nproc)" bcmath exif gd intl pdo_mysql mysqli zip opcache

RUN docker-php-ext-install ldap

RUN curl -fsSL https://downloads.mongodb.com/compass/mongodb-mongosh_2.5.9_amd64.deb -o mongosh.deb && \
    dpkg -i mongosh.deb && \
    rm mongosh.deb

WORKDIR /var/www/

#RUN git clone -b v7.1.1 https://github.com/krakjoe/uopz.git && \ # For PHP8
#RUN git clone -b v6.1.2 https://github.com/krakjoe/uopz.git && \
#    (cd uopz; phpize; ./configure --enable-uopz; make; make test; make install)

#RUN wget https://xdebug.org/files/xdebug-3.2.0.tgz && \
#    tar xfvz ./xdebug-*.tgz && \
#    (cd xdebug-*/; phpize; ./configure --enable-xdebug; make; make test; make install)

RUN pecl install uopz
RUN pecl install xdebug
RUN pecl install pcov

RUN rm /bin/sh && ln -s bash /bin/sh

COPY ./configs/php.ini "$PHP_INI_DIR/php.ini"
COPY ./configs/mpm_prefork.conf /etc/apache2/mods-available/mpm_prefork.conf
COPY ./instrumentation/ /var/www/fuzzer/
COPY ./entrypoint.sh /

COPY --from=composer:latest@sha256:1ac7a547cb88acb0de62663b70f2b3d80ad27355288245159404b6ae40cd9ca3 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html/
ENTRYPOINT ["/entrypoint.sh"]

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]