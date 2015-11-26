tb=`date '+%D %T'`
cd /var/www/TheptarinSMS
php5 TheptarinSMS.php >> /var/www/TheptarinSMS/TheptarinSMS.log
te=`date '+%D %T'`
echo "$tb , TheptarinSMS.sh working transfer , $te , TheptarinSMS.sh complete transfer " >>  /var/www/TheptarinSMS/csv_TheptarinSMS.log
