FROM personnelink/shellish

RUN apt-get update && \
    apt-get install -y postgresql-client && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*
RUN POSTGRES_INCLUDE=/usr/include/postgresql \
    POSTGRES_LIB=/usr/lib/ \
    cpan -f -i Pg
COPY tool/ /tool/
COPY skel/ /skel/
COPY fixtures/ /fixtures/
ENTRYPOINT ["python",  "/tool/cli.py"]
