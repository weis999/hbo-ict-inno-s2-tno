#!/bin/bash
export DEBIAN_FRONTEND=noninteractive
echo mysql-server mysql-server/root_password select xkW8hP7gTwpMk | debconf-set-selections
echo mysql-server mysql-server/root_password_again select xkW8hP7gTwpMk | debconf-set-selections

# Variables
keyphrase="iwuehifuhweiufhwe"
WP_DB_NAME="WordPress"
WP_DB_USERNAME="testuser01"
WP_DB_PASSWORD="Welkom01"
wpURL="html"
PUB_IP="$(sudo curl -s checkip.dyndns.org | sudo sed -e 's/.*Current IP Address: //' -e 's/<.*$//')"
MYSQL_PASS="xkW8hP7gTwpMk"
WP_DOMAIN="$(hostname)"
WP_ADMIN_USERNAME="testuser01"
WP_ADMIN_PASSWORD="Welkom01"
WP_ADMIN_EMAIL="admin@ubuntu.org"

# CREATE DATABASE + USER + IDENTIFIED PASSWORD + All PRIVILEGES
sudo echo "CREATE DATABASE WordPress;" | mysql -p$MYSQL_PASS
sudo echo "CREATE USER 'testuser01'@'localhost' IDENTIFIED BY 'Welkom01';" | mysql -p$MYSQL_PASS
sudo echo "GRANT ALL PRIVILEGES ON *.* TO 'testuser01'@'localhost';" | mysql -p$MYSQL_PASS
sudo echo "FLUSH PRIVILEGES;" | mysql -p$MYSQL_PASS
sudo echo "New MySQL database is successfully created"
sleep 2

# Finding Public IP of Server #
sudo echo "ServerName $PUB_IP">> /etc/apache2/apache2.conf
sudo echo "ServerName $WP_DOMAIN" >> /etc/apache2/apache2.conf

sudo curl "http://$PUB_IP/wp-admin/install.php?step=2" \
 --data-urlencode "weblog_title=$WP_DOMAIN"\
 --data-urlencode "user_name=$WP_ADMIN_USERNAME" \
 --data-urlencode "admin_email=$WP_ADMIN_EMAIL" \
 --data-urlencode "admin_password=$WP_ADMIN_PASSWORD" \
 --data-urlencode "admin_password2=$WP_ADMIN_PASSWORD" \
 --data-urlencode "pw_weak=1"
