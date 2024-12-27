#!/bin/bash

name=$1
password=$2
privileges=$3

if [ -z "$name" ]; then
  echo "User name is required"
  exit 1
fi

if [ -z "$password" ] && [ -z "$privileges"]; then
  useradd -m -s /usr/bin/fish $name
  usermod -aG $name
  exit 0
fi

# Assign fish as the default shell
chsh -s /usr/bin/fish

# Create a user with sudo privileges
if [ "$privileges" == "sudo" ]; then

  useradd -m -s /usr/bin/fish $name
  usermod -aG sudo $name

  if [ -n "$password" ]; then
    echo "$name:$password" | chpasswd && echo "root:$password" | chpasswd
  fi

  mkdir -p /home/$name/.config/fish
  echo -e "# Greeting \nset fish_greeting \"\" \nneofetch \nstarship init fish | source" >/home/$name/.config/fish/config.fish

  chown -R $name:$name /home/$name
fi

exit 0
