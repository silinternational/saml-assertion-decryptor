#!/usr/bin/env bash

composer update

apache2ctl -D FOREGROUND
