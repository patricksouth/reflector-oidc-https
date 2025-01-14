IMAGE_NAME=reflector-oidc-https
TAG=1

build:
	docker build -t $(IMAGE_NAME):$(TAG) .

start:
	./deploy_reflector_oidc.sh #	docker compose up -d

stop:
	docker stack rm reflector-oidc

restart:
	docker stack rm reflector-oidc
	sleep 10
	./deploy_reflector_oidc.sh

log:
	docker service logs -f reflector-oidc_httpd


