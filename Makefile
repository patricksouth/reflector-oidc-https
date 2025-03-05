IMAGE_NAME=reflector-oidc
TAG=1

build:
	docker build -t $(IMAGE_NAME):$(TAG) .

start:
	./deploy_reflector_oidc.sh

stop:
	docker stack rm reflector-oidc

restart:
	docker stack rm reflector-oidc
	sleep 5
	./deploy_reflector_oidc.sh

log:
	docker service logs -f reflector-oidc_httpd

