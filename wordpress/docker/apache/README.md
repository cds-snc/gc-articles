# Apache sidecar deployment

Making changes to any files in this directory and merging to main through a PR will trigger a new Apache container
build and deploy. 

In order to deploy the new container, you will need to create a separate PR updating the Apache entry in the
`infrastructure/environments.yml` file.

## Steps to deploy
- Create PR for updates (ex. container version or config changes)
- Make changes in `wordpress/docker/apache/`
- Bump version in `wordpress/docker/apache/VERSION`
- Merge PR (New container is deployed with tag from VERSION file)
- Create Release PR
- Bump version(s) in `infrastructure/environments.yml`
- Merge PR (New container is deployed to the environment(s))