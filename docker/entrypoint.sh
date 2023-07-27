#!/usr/bin/env sh
set -e
export APP=${APP:-teamwork-php-api}

function log() {
  local date=$(date -u +%Y-%m-%dT%H:%M:%S%z)
  local status="$1"
  shift
  local message=$(echo "$@" | sed s/$/\\n/g)
  printf '{"time":"%s","status":"%s","message":"%s"}\n' "$date" "$status" "$message"
}

# We need to know if flavor is set
if [ ! -z ${FLAVOR+x} ] ; then
  log "INFO" "Starting APP=${APP} with FLAVOR=${FLAVOR}"
else
  log "WARN" "WARNING: No FLAVOR, environment may not load properly."
fi

if [[ ${CHAMBER_ENABLED} == false ]]; then
  cp /tmp/dist.env.loc /var/www/html/.env.local
  cp /tmp/dist.env.loc /var/www/html/.env.test
  log "INFO" "CHAMBER_ENABLED is ${CHAMBER_ENABLED} so skipping attempt to load environment via chamber"
else
  chamber_environments="global $FLAVOR $APP $SHARED ${FLAVOR:+$APP-$FLAVOR}"
  log "INFO" "Attempting to load environment variables from SSM parameter store via chamber for: $chamber_environments"
  # If FLAVOR=dev then: chamber exec global dev $APP $APP-dev
  # If FLAVOR is not set or is empty, then: chamber exec global $APP
  chamber -r 3 exec $chamber_environments -- sh -c 'export -p' > /tmp/env 2>/tmp/chamber-stderr || {
    log "ERROR" "$(cat /tmp/chamber-stderr)" ;
    log "ERROR" "chamber failed, exiting"
    rm -f /tmp/env /tmp/chamber-stderr ;
    exit 1 ;
  }
  source /tmp/env
  rm -f /tmp/env
  # Expect `APP_FLAVOR` in the environment as a safety measure. If it's not set, assume chamber failed in a way not detected above.
  if [ -z "${APP_FLAVOR}" ] ; then
    log "ERROR" "ERROR: Did not detect APP_FLAVOR in the environment, assuming bad chamber load and exiting"
    exit 1
  else
    log "INFO" "APP_FLAVOR found: ${APP_FLAVOR}"
  fi
fi

cd /var/www/html
export COMPOSER_ALLOW_SUPERUSER=1
composer install
chown -R www-data:www-data ../html
composer dump-env ${FLAVOR}
php bin/console doctrine:migrations:migrate --em=teamwork --no-interaction
php-fpm -D
nginx -g 'daemon off;'
