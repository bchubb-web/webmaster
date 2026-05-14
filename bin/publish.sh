#!/bin/bash

# echo cwd
echo "Current working directory: $(pwd)"

# copy all files from ./build to the root of the project
cp -r ./vendor/bchubb-web/webmaster/build/* ./
