#!/bin/bash
#
#
# Install dependencies only for Docker.
set -xe
docker build --rm -t hub.ahss.io/ahss/fh-neuroscience:$CI_BUILD_REF_NAME .
docker login --username="tsms01" --password="$HUB_PASSWORD" hub.ahss.io
docker push hub.ahss.io/ahss/fh-neuroscience:$CI_BUILD_REF_NAME