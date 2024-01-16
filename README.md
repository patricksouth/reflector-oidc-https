# README

Deploys an attribute reflector for an **OIDC RP** that displays attributes release by the **OIDC OP** after successful authenication. This reflector is useful for troubleshooting OIDC claims including decoding encoded access tokens.

Register this RP with an OIDC OP before starting this service.

This reflector uses certs/keys managed by a Traefik proxy.
Traefik retrieves and stores web SSL certs in the ```$LETSENCRYPT``` path the first time it runs and ensures they are current when using the **deploy_reflector_oidc.sh** script.

You can use your own certs by placing them in the ./letsencrypt/{certs,private} directories respectively, before starting this service.

Otherwise, let this script create the symlink to the ${LETSENCRYPT} location (specified in the ```reflector.env``` file).

### Traefik proxy.
The Traefik Proxy is a quick and easy way to proxy services (on the same host with different FQDN) and manage Letsencrypt certificates for the proxied services.

Proxied services can have their own public DNS hostnames, which can differ from the Traefik service FQDN (also a public DNS hostname).

Some proxied services may NOT be able to consume the Letsencrypt acme.json file directly, that's where the ```dumpcerts.acme.v2.sh``` utility is handy for extracting the public/private SSL cert pair from the acme.json file.

An understanding of Traefik **labels** is essential when deploying proxied client services with Traefik.
Read more about **labels** here: [https://doc.traefik.io/traefik/routing/providers/docker/](https://doc.traefik.io/traefik/routing/providers/docker/).

A deployable Traefik proxy is available here if you need one:
[https://github.com/patricksouth/traefik-proxy](https://github.com/patricksouth/traefik-proxy).

## Configuration Before Deploying

Copy the example file ```src/auth_openidc.conf.example``` to ```src/auth_openidc.conf``` as a starting OIDC RP config and update with relevant details.
See [https://github.com/OpenIDC/mod_auth_openidc/blob/master/auth_openidc.conf](https://github.com/OpenIDC/mod_auth_openidc/blob/master/auth_openidc.conf)
for all config items and descriptions

Copy ```reflector.env.default``` to ```reflector.env``` and update the following

```LETSENCRYPT``` - set a suitable path to acme.json

```APACHE_FQDN``` - add the public DNS hostname for the service

## USAGE

Build the Docker image with: 

```
./build.sh
```

Deploy the service with:

```
./deploy_reflector_oidc.sh
```

## Notes

If you wish, set the Apache servername in ```src/sites-available/oidc-apache-site.conf```.
