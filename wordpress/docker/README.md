# WordPress Docker
Creates the Docker image used to run WordPress locally and in the AWS Elastic Container Service (ECS) Fargate cluster.

## Self-signed certificates
In AWS, the requests between the Application Load Balancer (ALB) and the WordPress container are encrypted using a self-signed certificate.  This certificate is injected as part of the Docker image build using the `APACHE_CERT` and `APACHE_KEY` build arguments.

```sh
# Generate the certificate
openssl req -x509 -nodes \
    -days 365 \
    -newkey rsa:2048 \
    -keyout self-signed.key \
    -out self-signed.crt

# Set required variables
export APACHE_KEY="$(cat self-signed.key)"
export APACHE_CERT="$(cat self-signed.crt)"

# Build the Docker image
docker build \
    --build-arg APACHE_KEY="$APACHE_KEY" \
    --build-arg APACHE_CERT="$APACHE_CERT" \
    -t platform-mvp/ircc:"$GITHUB_SHA" \
    -f ./wordpress/docker/Dockerfile .
```