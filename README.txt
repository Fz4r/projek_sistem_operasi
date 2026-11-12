CARA MENJALANKAN DI LARAGON
1. Salin folder ini ke C:\laragon\www\webapp
2. Start All di Laragon (Apache + MySQL).
3. Buka HeidiSQL (Menu > Database > HeidiSQL) lalu jalankan isi database.sql
   (atau import file tersebut).
4. Buka http://localhost/webapp  (atau http://webapp.test)

DEPLOY KE UBUNTU (Apache)
sudo apt update && sudo apt install apache2 mysql-server php php-mysql libapache2-mod-php -y
sudo mysql < database.sql
sudo mysql -e "CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'PasswordKuat123!'; GRANT ALL ON app_db.* TO 'app_user'@'localhost'; FLUSH PRIVILEGES;"
sudo mkdir /var/www/html/webapp && sudo cp -r * /var/www/html/webapp/
sudo chown -R www-data:www-data /var/www/html/webapp
Lalu edit config.php: DB_USER = 'app_user' dan DB_PASS = 'PasswordKuat123!' (ganti sesuai keinginan).
