complete -W "$(cat Makefile | grep --no-color '^[a-z]' | sed 's/://' | awk '{print $1}')" make
