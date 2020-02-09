#!/usr/bin/env bash

install_path="/opt/phpjail"

declare -a versions=(
    "7.0.33"
    "7.1.30"
    "7.2.19"
    "7.3.14"
)

for idx in "${!versions[@]}"
  do
    if [ "$idx" == 0 ]; then
      echo "first"
    else
      echo "${install_path}/${versions[idx]}" 
    fi
  done
