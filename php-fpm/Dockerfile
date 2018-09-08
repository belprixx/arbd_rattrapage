FROM phpdockerio/php72-fpm:latest
WORKDIR "/application"

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install  php7.2-mysql php7.2-mongodb \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install yarn, nano and cron
RUN apt-get update \
    && apt-get -y install nano \
    && apt-get install --no-install-recommends yarn \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN touch /etl.log
RUN chmod 777 /etl.log
RUN echo 'Europe/Paris' > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata

CMD service php7.2-fpm start && \
    composer update && \
	bin/console doctrine:schema:update --force && \
#	bin/console cache:clear --env=dev && \
	bin/console cache:clear --env=dev && \
#	/etc/init.d/cron start && \
	tail -f /etl.log
