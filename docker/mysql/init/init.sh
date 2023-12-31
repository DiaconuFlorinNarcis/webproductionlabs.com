#!/bin/bash
set -e

mysql << EOSQL
CREATE DATABASE `$MYSQL_DATABASE`;
CREATE USER '$MYSQL_USER'@'%' IDENTIFIED BY '$MYSQL_PASSWORD';
GRANT ALL PRIVILEGES ON *.* TO '$MYSQL_USER'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;

CREATE USER 'trigger_definer'@'%' IDENTIFIED BY 'trigger_definer';
GRANT ALL PRIVILEGES ON *.* TO 'trigger_definer'@'%';
FLUSH PRIVILEGES;

CREATE USER 'function_definer'@'%' IDENTIFIED BY 'function_definer';
GRANT ALL PRIVILEGES ON *.* TO 'function_definer'@'%';
FLUSH PRIVILEGES;
EOSQL
