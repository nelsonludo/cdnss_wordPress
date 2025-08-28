# Readme file

Extract database :

docker exec -it cdnss_db mysqldump -uwordpressuser -pvotremotdepasse wordpress_db > db.sql

Import database :

docker exec -i cdnss_db mysql -uwordpressuser -pvotremotdepasse wordpress_db < db.sql
