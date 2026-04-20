#!/usr/bin/env bash
npm i
rm ./laravel-echo-server.lock
node ./node_modules/.bin/laravel-echo-server start
