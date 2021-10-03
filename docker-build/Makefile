name = takuya/cron-laravel
.PHONY: all build 
REPO_URL=https://github.com/takuya/cronjob-alternative.git


all: build

build.log: ./Dockerfile ./nginx.conf ./supervisord.conf ./php-fpm-www.conf
	@docker build -t $(name) .
	date +"%Y-%m-%d %H:%M:%S "> ./build.log

build: cronjob-alternative build.log

cronjob-alternative: 
	git clone $(REPO_URL)

run:
	@docker run -it -d $(name)

## makefile 中での $() は $$() にする
login:
	docker exec -it $$( docker ps -q  --filter 'ancestor=$(name)' -f 'status=running' )  bash


kill:
	docker kill $$( docker ps -q  --filter 'ancestor=$(name)' -f 'status=running' )
