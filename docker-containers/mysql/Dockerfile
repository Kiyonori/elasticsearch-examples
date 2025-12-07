FROM mysql:8.0.41

ARG MYSQL_USER
ARG MYSQL_PASSWORD

RUN { \
    echo "ALTER USER '${MYSQL_USER}'@'%' IDENTIFIED WITH mysql_native_password BY '${MYSQL_PASSWORD}';"; \
    echo "SET GLOBAL collation_server = 'utf8mb4_unicode_520_ci';"; \
} > ./docker-entrypoint-initdb.d/user_init.sql