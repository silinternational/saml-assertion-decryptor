#!/usr/bin/env bash

composer update

cp ./config.php $SSP_PATH/config/config.php

apache2ctl -D FOREGROUND
