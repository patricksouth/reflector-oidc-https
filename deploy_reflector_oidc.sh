#!/bin/bash
set +x
set -o errexit
set -o nounset
set -o pipefail

# 16-Jan-2024

cd "$(dirname "$0")"
source reflector.env

# This reflector can use certs/keys managed by a Traefik proxy.
# Traefik retrieves and stores web SSL certs in the $LETSENCRYPT path the first time it runs and ensures they are current.
# You can use your own certs by placing them in the ./letsencrypt/{certs,private} directories respectively, before starting this services.
# Otherwise, let this script create the symlink to the ${LETSENCRYPT} location (specified in the reflector.env file).
# See README.md for details on the Traefik proxy.

if [[ ! -d ./letsencrypt && -d ${LETSENCRYPT} ]]; then
  ln -s ${LETSENCRYPT} ./letsencrypt
fi

# Extract the certs managed by Traefik stored in acme.json
if [[ -e ./letsencrypt/acme.json ]]; then
  if [ ! -x "$(which jq)" ]; then
    yum update -y && yum -y install jq && yum clean all
  fi
  pushd ./letsencrypt
  ./dumpcerts.acme.v2.sh ./acme.json ./
  chmod go-rwx ../
  popd
fi

if [[ ! -e letsencrypt/certs/${APACHE_FQDN}.crt || ! -e letsencrypt/private/${APACHE_FQDN}.key ]];then
  echo
  echo "     Missing SSL cert or key files at 'letsencrypt/certs' OR 'letsencrypt/private' "
  echo "     for the host ${APACHE_FQDN}."
  echo
  exit
fi

if [ ! -d "logs" ]; then
  mkdir {logs,tmp}
fi

if [[ -n $(docker stack ls --format '{{.Name}}' | grep reflector-oidc) ]]; then
  echo
  echo "removing reflector-oidc ... wait"
  echo
  docker stack rm reflector-oidc
  sleep 5
fi

docker stack deploy --compose-file docker-compose.yml reflector-oidc

echo
echo "    Want to display logs, use"
echo " docker service logs -f reflector-oidc_httpd"
echo
