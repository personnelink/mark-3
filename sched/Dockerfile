FROM __PROJECT__-app-base:__BRANCH__


RUN echo "US/Mountain" > /etc/timezone && \
    dpkg-reconfigure --frontend noninteractive tzdata
RUN apt-get update && \
    apt-get install -y \
        python3 \
        python3-pip && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

ENV LANG=C.UTF-8 \
    PATH="/tasks:$PATH"

RUN composer require elasticsearch/elasticsearch:~5.0
RUN pip3 install https://github.com/person/cronredux/archive/bc1c80a9a35bf26f1c0f510145ba37246f70b3ee.zip

COPY conf/php/ /usr/local/etc/php/conf.d/
COPY tasks /tasks
COPY crontab /

EXPOSE 4180

ENTRYPOINT ["cronredux", "/crontab"]
