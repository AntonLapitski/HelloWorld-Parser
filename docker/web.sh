#!/bin/bash

export HOST="$(curl -m2 -s 169.254.169.254/latest/meta-data/local-hostname)"
export LOCAL_IP="$(curl -m2 -s 169.254.169.254/latest/meta-data/local-ipv4)"

/usr/sbin/apache2ctl -D FOREGROUND
