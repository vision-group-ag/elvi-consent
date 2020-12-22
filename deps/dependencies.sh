#!/usr/bin/env bash

function setupSSH()
{
    which ssh-agent || (apk add --update --no-cache openssh-client)
    eval $(ssh-agent -s)
    echo "$SSH_PRIVATE_KEY" | tr -d '\r' | ssh-add - > /dev/null

    mkdir -p ~/.ssh
    chmod 700 ~/.ssh

    ssh-keyscan gitlab.com >> ~/.ssh/known_hosts
    chmod 644 ~/.ssh/known_hosts
}

function startWebServer()
{
    local location=$1
    cd ${location}/web && php -S localhost:8888 &

    cd ${location}
}
