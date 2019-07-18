#!/usr/bin/env bash

# Bail out on first error
set -e

## Get the remote copies of these scripts
## aws s3 cp s3://det-cloudformation-backups/environments/docker_build_deploy.sh deploy/docker_build_deploy.sh
bash deploy/docker_build_deploy.sh

aws s3 cp s3://det-cloudformation-backups/environments/service_update.py deploy/service_update.py
python deploy/service_update.py $APP_NAME-$STACK_ENV_TAG
