<? 
  defined( '_JEXEC' ) or die( 'Restricted access' );
  
// Переменные


// Функция получения JSON с помощью cURL
// function get_json($url){
//   $ch = curl_init();
//   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//   curl_setopt($ch, CURLOPT_URL, $url);
//   $res = curl_exec($ch);
//   curl_close($ch);
//   return $res;
// }
// // Функция чтения cache
// function read_cache($cache){
//   $handle = fopen($cache, 'r');
//   $cache_read = fgets($handle);
//   fclose($handle);
//   return $cache_read;
// }
// // Функция записи cache
// function write_cache($cache, $url){
// $handle = fopen($cache, 'w+');
// fwrite($handle, get_json($url));
// fclose($handle);
// }

// //Проверка cache и получение json
// if(file_exists($cache)){
//   $file_time = time() - filectime($cache);
// };
// if (file_exists($cache) && $file_time <= 2){
//   $data = json_decode(read_cache($cache));
// } else {
//   write_cache($cache, $urls[0]);
//   $data = json_decode(read_cache($cache));
// };
// // Записываем курсы в переменные
// $usd_rate = substr($data->query->results->rate[0]->Rate, 0, -2);
// $eur_rate = substr($data->query->results->rate[1]->Rate, 0, -2);
//Считаем разницу в курсах
// $usd_diff = $old_usd_rate - $usd_rate;
// $eur_diff = $old_eur_rate - $eur_rate;


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// MULTICURL
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Переменные


//***************************************************************************************//
// ~4sec.
// $url_count = count($urls);

// $curl_arr = array();
// $mch = curl_multi_init();

// for($i = 0; $i < $url_count; $i++)
// {
//     $url =$urls[$i];
//     $curl_arr[$i] = curl_init();
//     curl_setopt($curl_arr[$i], CURLOPT_SSL_VERIFYPEER, false);
//     curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($curl_arr[$i], CURLOPT_URL, $url);
//     curl_multi_add_handle($mch, $curl_arr[$i]);
// }

// do {
//     curl_multi_exec($mch,$running);
// } while($running > 0);

// for($i = 0; $i < $url_count; $i++)
// {
//     $results = curl_multi_getcontent  ( $curl_arr[$i]  );
//     echo( $i . "\n" . $results . "\n");
// }

// for($i = 0; i< $url_count; $i++) {
//     curl_multi_remove_handle($mh, $curl_arr[$i]);
// }
// curl_multi_close($mch);
// echo 'done';

//************************************************************************************//
// 4sec.
$urls = array(
    'https://query.yahooapis.com/v1/public/yql?q=select+Rate+from+yahoo.finance.xchange+where+pair+=+%22USDRUB,EURRUB%22&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=',
    'https://query.yahooapis.com/v1/public/yql?q=select%20item.long%20from%20weather.forecast%20where%20woeid%3D2124298%0A&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys',
    'https://query.yahooapis.com/v1/public/yql?q=select%20DaysHigh%20from%20yahoo.finance.quote%20where%20symbol%20in%20(%22BZM16.NYM%22)&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback='
  );

$cache = 'cache/rates.cache';

$n = count($urls);


$ch1 = curl_init();
$ch2 = curl_init();
$ch3 = curl_init();

for($i; i<$n; $i++){
  curl_setopt($ch[$i], CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch[$i], CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch[$i], CURLOPT_URL, $urls[$i]);
  curl_setopt($ch[$i], CURLOPT_HEADER, 0);
}

// create the multiple cURL handle
$mh = curl_multi_init();
 
// добавляем обработчики
curl_multi_add_handle($mh,$ch1);
curl_multi_add_handle($mh,$ch2);
// curl_multi_add_handle($mh,$ch3);
 
$running = null;
// выполняем запросы
do {
    curl_multi_exec($mh, $running);
} while ($running > 0);
 
// освободим ресурсы
curl_multi_remove_handle($mh, $ch1);
curl_multi_remove_handle($mh, $ch2);
curl_multi_remove_handle($mh, $ch3);
curl_multi_close($mh);



require(JmoduleHelper::getLayoutPath('mod_currency'));
?>