FROM php:8.1 AS base
ENV TZ=America/Guayaquil
WORKDIR /code
ARG name=mydealer
ARG password
ARG permissions
COPY --chmod=755 ./scripts ./scripts
RUN ./scripts/packages.sh \
  && ./scripts/user.sh $name $password $permissions

FROM base AS builder
COPY --from=composer /usr/bin/composer /usr/bin/composer

FROM builder AS installer
COPY composer.* ./
RUN composer install --no-scripts

FROM base as runner
ARG name
COPY --from=installer /code/vendor /code/vendor
RUN chown -R $name:$name ./vendor && chmod -R 755 ./vendor
COPY --chown=$name:$name . .
EXPOSE 8000
USER $name
CMD ["php", "artisan", "serve", "--host", "0.0.0.0"]
