FROM zaproxy/zap-stable:latest
USER root
RUN apt-get update -y && apt-get install python3 python3-pip

USER zap
WORKDIR /zap

RUN pip install --break-system-packages requests

COPY phuzz.py ./
COPY run.py ./
COPY init.sh ./

RUN mkdir -p /tmp/zap/wrk
RUN ln -s /tmp/zap/wrk /zap/wrk

ENTRYPOINT bash ./init.sh
