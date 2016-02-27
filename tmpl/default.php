<p>
  <!-- TODO Подключить скрипты температуры -->
  Волгоград <span class="temperature">
  <? if ($temperature >= 0):?>
  <span class="hot-t">+ <?= $temperature ?>&deg;C</span>
  <? else: ?>
  <span class="cold-t"><?= $temperature?>&deg;C</span>
<? endif; ?>
  <!-- TODO Подключить разницу курсов -->
  | USD - <?= $usd_rate; ?> <span class="curency-up">0.48</span>  EUR - <?= $eur_rate;  ?> <span class="curency-down">0.68</span>
  <!-- TODO Подключить разницу курсов -->
  <span class="desktop">НЕФТЬ - <?= $oil; ?> <span class="curency-down">0.41%</span></span>
</p>