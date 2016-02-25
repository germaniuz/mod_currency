<? 
 // defined( '_JEXEC' ) or die( 'Restricted access' );
  
// Переменные
$url = 'https://query.yahooapis.com/v1/public/yql?q=select+Rate+from+yahoo.finance.xchange+where+pair+=+%22USDRUB,EURRUB%22&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=';
// url for C Volgograd
// https://query.yahooapis.com/v1/public/yql?q=select%20item.long%20from%20weather.forecast%20where%20woeid%3D2124298%0A&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys
// url for brent oil https://query.yahooapis.com/v1/public/yql?q=select%20DaysHigh%20from%20yahoo.finance.quote%20where%20symbol%20in%20(%22BZM16.NYM%22)&format=json&diagnostics=false&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=
$cache = 'cache/rates.cache';

// Функция получения JSON с помощью cURL
function get_json($url){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL, $url);
  $res = curl_exec($ch);
  curl_close($ch);
  return $res;
}
// Функция чтения cache
function read_cache($cache){
  $handle = fopen($cache, 'r');
  $cache_read = fgets($handle);
  fclose($handle);
  return $cache_read;
}
// Функция записи cache
function write_cache($cache, $url){
$handle = fopen($cache, 'w+');
fwrite($handle, get_json($url));
fclose($handle);
}

//Проверка cache и получение json
if(file_exists($cache)){
  $file_time = time() - filectime($cache);
};
if (file_exists($cache) && $file_time <= 86400){
  $data = json_decode(read_cache($cache));
} else {
  write_cache($cache, $url);
  $data = json_decode(read_cache($cache));
};
// Записываем курсы в переменные
$usd_rate = substr($data->query->results->rate[0]->Rate, 0, -2);
$eur_rate = substr($data->query->results->rate[1]->Rate, 0, -2);
//Считаем разницу в курсах
// $usd_diff = $old_usd_rate - $usd_rate;
// $eur_diff = $old_eur_rate - $eur_rate;

require(JmoduleHelper::getLayoutPath('mod_currency'));
?>