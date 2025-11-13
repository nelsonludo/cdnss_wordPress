# Readme file

Extract database :

mysqldump -uwordpressuser -pvotremotdepasse wordpress_db > db.sql

//powershell

mysqldump -uwordpressuser -pvotremotdepasse wordpress_db | Out-File -Encoding utf8 db.sql

Import database :

mysql -uwordpressuser -pvotremotdepasse wordpress_db < db.sql

//powershell

mysql -uwordpressuser -pvotremotdepasse wordpress_db
