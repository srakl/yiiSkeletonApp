sudo apt-get update

sudo apt-get install -y python-software-properties

sudo add-apt-repository -y ppa:onderj/php5-oldstable

sudo apt-get update

sudo apt-get install -y curl

sudo apt-get install -y php5 php5-curl php5-fpm php-pear php5-common php5-mcrypt php5-mysql php5-cli php5-gd php5-imagick php5-intl

sudo apt-get install -y nginx

sudo mv /etc/nginx/nginx.conf /etc/nginx/nginx.conf.backup

sudo cp /srv/vm/nginx.conf /etc/nginx/nginx.conf

sudo cp /srv/vm/phpfcgi.conf /etc/nginx/conf.d

sudo cp /srv/vm/app.conf /etc/nginx/sites-enabled

sudo service nginx restart

sudo apt-get install -y git-core