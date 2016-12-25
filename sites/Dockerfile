FROM php:7.0.2-apache
VOLUME /var/www/site
ARG proc=live
COPY skel/common /var/www/site
COPY skel/$proc /var/www/site
CMD /bin/true
