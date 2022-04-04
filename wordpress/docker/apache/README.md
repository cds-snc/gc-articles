# Apache sidecar deployment

Making changes to any files in this directory and merging to main through a PR will trigger a new Apache container
build and push to ECR (both Staging and Production).

In order to deploy the new container, you will need to create a separate PR updating the Apache entry in the
`infrastructure/environments.yml` file.

## Steps to deploy
### 1. Prepare container(s)
- Make changes to any files in `wordpress/docker/apache/`
- Bump version in `wordpress/docker/apache/VERSION`
- Create PR
- Merge PR (New container is pushed to ECR with tag from VERSION file)
### 2. Deploy container(s)
- Bump version(s) in `infrastructure/environments.yml`
- Create Release PR
- Merge PR (New container is deployed to the environment(s))