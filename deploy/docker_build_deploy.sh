#!/usr/bin/env bash

# Bail out on first error
set -e


## Get the directory of the build script
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

## Get the current git commit sha
HASH=$(git rev-parse HEAD)

echo "Going to sync up file from root to packaged area"
echo "This assumes yarn run production/dev was run before hand"
cd $DIR/../
rsync -ralq --delete --progress --ignore-errors --exclude=node_modules/laravel-elixir --exclude=.gitignore --exclude=.vscode --exclude=.idea --exclude=deploy --exclude=.git --exclude=.env . $DIR/app/packaged

echo "Setting up filebeat staging make sure file exists"
cp $DIR/app/filebeat_staging.yml $DIR/app/filebeat.yml


echo "Get Key for Box"
aws s3 cp s3://det-$APP_NAME/private_key_box.pem $DIR/app/packaged/private_key_box.pem

echo "Getting ENV file from S3 s3://det-cloudformation-backups/environments/$STACK_ENV_FILE"
aws s3 cp s3://det-cloudformation-backups/environments/$STACK_ENV_FILE $DIR/app/packaged/.env
echo "Getting Core Env Settings"
aws s3 cp s3://det-cloudformation-backups/environments/env_core $DIR/app/packaged/env_core
printf "\n# ENV CORE SETTINGS\n" >> $DIR/app/packaged/.env
cat $DIR/app/packaged/env_core >> $DIR/app/packaged/.env
printf "\n# END ENV CORE SETTINGS\n" >> $DIR/app/packaged/.env
rm $DIR/app/packaged/env_core
echo "Getting your env additions"
aws s3 cp s3://det-$APP_NAME/provision/$STACK_ENV_TAG/env_additions $DIR/app/packaged/env_additions
printf "\n# ENV ADDITIONS\n" >> $DIR/app/packaged/.env
cat $DIR/app/packaged/env_additions >> $DIR/app/packaged/.env
printf "\n# END ENV ADDITIONS\n" >> $DIR/app/packaged/.env
rm $DIR/app/packaged/env_additions

cd $DIR/app/packaged

eval $(aws ecr get-login --no-include-email --region eu-west-1)
cd $DIR/app
echo "Tagging images $APP_NAME"
docker build --pull -t $APP_NAME .
docker tag $APP_NAME:latest 364215618558.dkr.ecr.eu-west-1.amazonaws.com/$APP_NAME:latest
echo "Pushing up image $APP_NAME:latest"
docker push 364215618558.dkr.ecr.eu-west-1.amazonaws.com/$APP_NAME:latest
##docker push 364215618558.dkr.ecr.eu-west-1.amazonaws.com/cat-teamdocs:$HASH
##git reset HEAD -- $DIR/app/packaged

## Now Run again for Production
## if production set???
if [[ "$STACK_ENV_FILE_PRODUCTION" ]]; then
    echo "Setting up filebeat production make sure file exists"
    cp $DIR/app/filebeat_production.yml $DIR/app/filebeat.yml

    echo "Running Production build"
    echo "Getting ENV file from S3 s3://det-cloudformation-backups/environments/$STACK_ENV_FILE_PRODUCTION"
    aws s3 cp s3://det-cloudformation-backups/environments/$STACK_ENV_FILE_PRODUCTION $DIR/app/packaged/.env
    echo "Getting Core Env Settings"
    aws s3 cp s3://det-cloudformation-backups/environments/env_core $DIR/app/packaged/env_core
    printf "\n# ENV CORE SETTINGS\n" >> $DIR/app/packaged/.env
    cat $DIR/app/packaged/env_core >> $DIR/app/packaged/.env
    printf "\n# END ENV CORE SETTINGS\n" >> $DIR/app/packaged/.env
    rm $DIR/app/packaged/env_core
    echo "Getting your env additions"
    aws s3 cp s3://det-$APP_NAME/provision/production/env_additions $DIR/app/packaged/env_additions
    printf "\n# ENV ADDITIONS\n" >> $DIR/app/packaged/.env
    cat $DIR/app/packaged/env_additions  >> $DIR/app/packaged/.env
    printf "\n# END ENV ADDITIONS\n" >> $DIR/app/packaged/.env
    rm $DIR/app/packaged/env_additions
    echo "Copy over production supervisor"
    cp $DIR/app/production_supervisor.ini $DIR/app/supervisor.ini
    echo "Building Production Image"
    docker build -t $APP_NAME .
    docker tag $APP_NAME:latest 364215618558.dkr.ecr.eu-west-1.amazonaws.com/$APP_NAME:production_$HASH
    echo "Pushing up production image using has production_$HASH"
    docker push 364215618558.dkr.ecr.eu-west-1.amazonaws.com/$APP_NAME:production_$HASH

    echo "Running UAT build"
    echo "Setting up UAT filebeat"
    cp $DIR/app/filebeat_uat.yml $DIR/app/filebeat.yml
    echo "Getting ENV file from S3 s3://det-cloudformation-backups/environments/$APP_NAME-uat"
    aws s3 cp s3://det-cloudformation-backups/environments/$APP_NAME-uat $DIR/app/packaged/.env
    echo "Getting Core Env Settings"
    aws s3 cp s3://det-cloudformation-backups/environments/env_core $DIR/app/packaged/env_core
    printf "\n# ENV CORE SETTINGS\n" >> $DIR/app/packaged/.env
    cat $DIR/app/packaged/env_core >> $DIR/app/packaged/.env
    printf "\n# END ENV CORE SETTINGS\n" >> $DIR/app/packaged/.env
    rm $DIR/app/packaged/env_core
    echo "Getting your env additions"
    aws s3 cp s3://det-$APP_NAME/provision/uat/env_additions $DIR/app/packaged/env_additions
    printf "\n# ENV ADDITIONS\n" >> $DIR/app/packaged/.env
    cat $DIR/app/packaged/env_additions  >> $DIR/app/packaged/.env
    printf "\n# END ENV ADDITIONS\n" >> $DIR/app/packaged/.env
    rm $DIR/app/packaged/env_additions
    echo "Copy over uat supervisor"
    cp $DIR/app/production_supervisor.ini $DIR/app/supervisor.ini
    echo "Building uat Image"
    docker build -t $APP_NAME .
    docker tag $APP_NAME:latest 364215618558.dkr.ecr.eu-west-1.amazonaws.com/$APP_NAME:uat_$HASH
    echo "Pushing up production image using has uat_$HASH"
    docker push 364215618558.dkr.ecr.eu-west-1.amazonaws.com/$APP_NAME:uat_$HASH
fi
