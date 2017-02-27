FROM silintl/php7:latest

MAINTAINER Phillip Shipley <phillip.shipley@gmail.com>

ENV REFRESHED_AT 2016-12-16

RUN apt-get update -y && \
    apt-get install -y vim && \
    apt-get install -y php-memcache && \
    apt-get clean

# Create required directories
RUN mkdir -p /data
RUN echo "colo blue">>~/.vimrc

COPY ./dockerbuild/vhost.conf /etc/apache2/sites-enabled/
COPY ./dockerbuild/run.sh /data/run.sh
COPY ./application/ /data/

WORKDIR /data

# Install/cleanup composer dependencies
COPY composer.json /data/
COPY composer.lock /data/
RUN composer self-update --no-interaction
RUN composer install --prefer-dist --no-interaction --no-dev --optimize-autoloader --no-scripts

# Copy in SSP override files
ENV SSP_PATH /data/vendor/simplesamlphp/simplesamlphp

RUN chmod a+x /data/run.sh 

EXPOSE 80
CMD ["/data/run.sh"]
