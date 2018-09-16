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

# Install yarn, nano
RUN apt-get update \
    && apt-get -y install nano \
    && apt-get install --no-install-recommends yarn \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN apt-get update && apt-get -y install cron

RUN touch /etl.log
RUN chmod 777 /etl.log
RUN echo 'Europe/Paris' > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata
#


ADD crontab /etc/cron.d/hello-cron
ADD backup.sh /
RUN chmod 755 /backup.sh
RUN mkdir -p /backup/mysql
RUN chmod 755 /etc/cron.d/hello-cron
RUN crontab /etc/cron.d/hello-cron
RUN touch /var/log/cron.log
#
CMD service php7.2-fpm start && \
    composer update && \
	bin/console doctrine:schema:update --force && \
	bin/console cache:clear --env=dev && \
	cron && tail -f /var/log/cron.log &&\
	tail -f /etl.log

