# PROJECT

git submodule init
git submodule update

tar --strip-components=1 -xvf ignoredFiles.tar

mkdir -p www/images/content
mkdir -p  www/file/content

chmod 777 www/images/content/
chmod 777 www/file/content/

composer update
npm install gulp gulp-compass gulp-concat gulp-minify-css gulp-plumber gulp-sourcemaps gulp-uglifyjs gulp-watch

# local.conf
read -p "Enter hostname for project:" host
cat conf/local.conf | sed -e "s/C_SESSION_COOKIE_DOMAIN', '.*');/C_SESSION_COOKIE_DOMAIN', '.$host');/g" > conf/local1.conf
mv conf/local1.conf conf/local.conf

# MYSQL
echo "**** SETUP MYSQL ****"
read -p "Enter user with create db permissions: " rootuser
read -p "Enter password: " rootpassword
echo ""
echo "[New database information]"
read -p "Database name: " dbname
read -p "User name for project: " dbuser
read -p "Password:" dbpassword

if [ -z "$rootpassword" ]
then
	roostr="";
else
	roostr="-p$rootpassword"
fi

mysql -u$rootuser $roostr -e "CREATE DATABASE $dbname COLLATE 'utf8_general_ci'"
mysql -u$rootuser $roostr -e "GRANT ALL ON $dbname.* TO $dbuser@localhost IDENTIFIED BY '$dbpassword';"
mysql -u$rootuser $roostr -e "FLUSH PRIVILEGES;"
mysql -u$rootuser $roostr $dbname < sql.sql

# db_default
cat conf/db_default.conf | sed -e "s/password = .*;/password = '$dbpassword';/g" -e "s/user = .*;/user = '$dbuser';/g"  -e "s/database = .*;/database = '$dbname';/g" > conf/db_default1.conf
mv conf/db_default1.conf conf/db_default.conf

