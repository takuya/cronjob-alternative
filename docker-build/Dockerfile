
FROM php:8.0-fpm

RUN  echo "APT::Install-Suggests 0;"   >> /etc/apt/apt.conf.d/00-no-install-recommends ;\
     echo "APT::Install-Recommends 0;" >> /etc/apt/apt.conf.d/00-no-install-recommends ;\
  curl -fsSL https://deb.nodesource.com/setup_16.x | bash - ;\
  sync;\  
  DEBIAN_FRONTEND=noninteractive apt-get update ; \
  DEBIAN_FRONTEND=noninteractive apt-get install -y  unzip p7zip-full nginx supervisor nodejs;\
  DEBIAN_FRONTEND=noninteractive apt-get install -y  sudo vim-nox procps bash-completion ;\
  /usr/local/bin/docker-php-ext-install pcntl  sysvshm ;\
  /usr/local/bin/docker-php-ext-enable opcache;\
  mkdir /run/php;\
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" &&\
  php composer-setup.php &&\
  php -r "unlink('composer-setup.php');" && \
  mv composer.phar /usr/local/bin/composer &&\
  echo done ;


COPY ./supervisord.conf /root/
COPY ./nginx.conf /root/
COPY ./php-fpm-www.conf /root
COPY ./cronjob-alternative/ /root/cronjob-alternatives

WORKDIR /root/cronjob-alternatives/src

RUN npm i ;\
  npm run prod;\
  echo done;

COPY ./.env.docker /root/cronjob-alternatives/src/.env

RUN composer install;\
  cp .env.docker .env ;\
  php artisan key:generate;\
  php artisan storage:link;\
  php artisan optimize:clear;\
  touch database/database.sqlite ; \
  export docker_build=true;\
  php artisan migrate:fresh --seed;\
  echo done;

EXPOSE 80

# 起動
CMD [ "supervisord", "-c", "/root/supervisord.conf" ]


