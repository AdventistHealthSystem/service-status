#!/bin/bash
#
set -xe
docker rm -f $CI_PROJECT_ID || echo 'No container to remove'
docker pull hub.ahss.io/ahss/fh-neuroscience:$CI_BUILD_REF_NAME
docker run -it -d -p 80 --label interlock.hostname="fh-neuroscience" \
    --label interlock.domain="dev.ahss.io" \
    --restart="always" \
    --name="$CI_PROJECT_ID" \
    hub.ahss.io/ahss/fh-neuroscience:$CI_BUILD_REF_NAME