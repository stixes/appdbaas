FROM ubuntu

RUN apt-get update && \
    apt-get install -y curl mysql-client jq

COPY run-tests.sh /

CMD /run-tests.sh
