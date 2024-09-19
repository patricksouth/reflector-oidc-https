IMAGE_NAME=reflector-oidc-https
TAG=1

build:
	docker build -t $(IMAGE_NAME):$(TAG) .

run:
	docker compose up -d

