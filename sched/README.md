Scheduled Tasks
========
All cron-esc tasks that need to run in the stack are kept here.  The current
design simply runs a monolithic container with a crontab file.  

Design
--------
 * **Single host/container**: Only one instance of the schedule container runs
   per stack (prod, stage, etc)
 * Concurrent task execution (up to 10).
 * Logs are shipped directly to Elasticsearch.
 * Errors create notifications in slack.
 * Horizontal scale applications must implement their own fanout logic and
   control.
 * Access to EFS /home is permitted as-is access to various AWS services such
   as S3, RDS, etc.
