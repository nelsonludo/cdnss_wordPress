# Readme file

Extract database :

docker exec -it cdnss_db mysqldump -uwordpressuser -pvotremotdepasse wordpress_db > db.sql

//powershell

docker exec -i cdnss_db mysqldump -uwordpressuser -pvotremotdepasse wordpress_db | Out-File -Encoding utf8 db.sql

Import database :

docker exec -i cdnss_db mysql -uwordpressuser -pvotremotdepasse wordpress_db < db.sql

//powershell

Get-Content db.sql | docker exec -i cdnss_db mysql -uwordpressuser -pvotremotdepasse wordpress_db
