<? 
  defined( '_JEXEC' ) or die( 'Restricted access' );
  

$urls = array(
    'https://query.yahooapis.com/v1/public/yql?q=select+Rate+from+yahoo.finance.xchange+where+pair+=+%22USDRUB,EURRUB%22&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=',
    'https://query.yahooapis.com/v1/public/yql?q=select%20item.condition.temp%20from%20weather.forecast%20where%20woeid%3D2124298%0A&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys',
    'https://query.yahooapis.com/v1/public/yql?q=select%20DaysHigh%20from%20yahoo.finance.quote%20where%20symbol%20in%20(%22BZM16.NYM%22)&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback='
  );

$cache = 'cache/rates.cache';

$ch1 = curl_init();
$ch2 = curl_init();
$ch3 = curl_init();
 
// устанавливаем опции
curl_setopt($ch1, CURLOPT_URL, $urls[0]);
curl_setopt($ch1, CURLOPT_HEADER, 0);
curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch2, CURLOPT_URL, $urls[1]);
curl_setopt($ch2, CURLOPT_HEADER, 0);
curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch3, CURLOPT_URL, $urls[2]);
curl_setopt($ch3, CURLOPT_HEADER, 0);
curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
 
//create the multiple cURL handle
$mh = curl_multi_init();
 
// добавляем обработчики
curl_multi_add_handle($mh,$ch1);
curl_multi_add_handle($mh,$ch2);
curl_multi_add_handle($mh,$ch3);

$running = null;
// выполняем запросы
do {
    curl_multi_exec($mh, $running);
} while ($running > 0);

$whandle = fopen($cache, 'w+');
fwrite($whandle, curl_multi_getcontent($ch1)."\r\n");
fwrite($whandle, curl_multi_getcontent($ch2)."\r\n");
fwrite($whandle, curl_multi_getcontent($ch3)."\r\n");
fclose($whandle);

// освободим ресурсы
curl_multi_remove_handle($mh, $ch1);
curl_multi_remove_handle($mh, $ch2);
curl_multi_remove_handle($mh, $ch3);
curl_multi_close($mh);

$data = array();

$rhandle = fopen($cache, 'r');
if ($rhandle) {
    while (($buffer = fgets($rhandle, 4096)) !== false) {
        array_push($data, json_decode($buffer));
    }
    if (!feof($rhandle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($rhandle);
}

$usd_rate = substr($data[0]->query->results->rate[0]->Rate, 0, -2);
$eur_rate = substr($data[0]->query->results->rate[1]->Rate, 0, -2);

$temperature = round(($data[1]->query->results->channel->item->condition->temp - 32) / 1.8);

require(JmoduleHelper::getLayoutPath('mod_currency'));
?>