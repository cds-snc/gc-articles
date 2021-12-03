# Apache sidecar deployment

Making changes to any files in this directory and merging to main through a PR will trigger a new Apache container
build and deploy. Along with any changes, you must update the version in the following files:

- wordpress/docker/apache/VERSION
- infrastructure/terragrunt/env/staging/ecs/terragrunt.hcl

The first is used to set the tag on the docker container, the second tells ECS to pull in the new container.