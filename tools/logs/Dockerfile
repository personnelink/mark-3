FROM personnelink/shellish

RUN curl -sSL https://get.docker.com/builds/Linux/x86_64/docker-1.11.2.tgz > docker.tar.gz && \
    tar zxvf docker.tar.gz docker/docker && \
    mv docker/docker /usr/bin/ && \
    rm -rf docker.tar.gz docker

COPY tool/ /tool/
ENTRYPOINT ["python",  "/tool/cli.py"]
