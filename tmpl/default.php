<?
$usd_diff >= 0 ? $usd_diff_class = "curency-up" : $usd_diff_class = "curency-down";
$eur_diff >= 0 ? $eur_diff_class = "curency-up" : $eur_diff_class = "curency-down";
$oil_diff >= 0 ? $oil_diff_class = "curency-up" : $oil_diff_class = "curency-down";
?>
<p>
  <!-- TODO Подключить скрипты температуры -->
  Волгоград <span class="temperature">
  <? if ($temperature >= 0):?>
  <span class="hot-t">+ <?= $temperature ?>&deg;C</span>
  <? else: ?>
  <span class="cold-t"><?= $temperature?>&deg;C</span>
<? endif; ?>
  <!-- TODO Подключить разницу курсов -->
  | USD - <?= $usd_rate; ?> <span class="<?= $usd_diff_class ?>"><?= $usd_diff ?></span>  EUR - <?= $eur_rate;  ?> <span class="<?= $eur_diff_class ?>"><?= $eur_diff ?></span>
  <!-- TODO Подключить разницу курсов -->
  <span class="desktop">НЕФТЬ - <?= $oil; ?> <span class="<?= $oil_diff_class ?>"><?= $oil_diff ?></span></span>
</p>