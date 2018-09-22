FROM percona

MAINTAINER Alireza Rahmani Khalili "alirezarahmani@live.com"

ENV TERM xterm

ADD my.cnf /etc/mysql/my.cnf
ADD 01_event_streams_table.sql /docker-entrypoint-initdb.d
ADD 02_projections_table.sql /docker-entrypoint-initdb.d
ADD 01_event_streams_table_test.sql /docker-entrypoint-initdb.d
ADD 02_projections_table_test.sql /docker-entrypoint-initdb.d
