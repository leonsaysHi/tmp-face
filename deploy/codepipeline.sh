#!/usr/bin/env bash

# Bail out on first error
set -e

echo "Zipping up files"
zip --quiet -r latest ./
echo "Sending zip to aws to trigger build"
aws --region=eu-west-1 s3 cp latest.zip s3://det-${APP_NAME}/provision/${STACK_ENV_TAG}/latest.zip

echo "Going to poll for job status"
cd deploy
echo "Install Pipeline watcher"
sudo pip3 install -q -r requirements.txt
python3 poll_pipeline.py
