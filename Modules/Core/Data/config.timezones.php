<?php

$timezones = [

    ['id'=>'Asia/Shanghai', 'text'=> 'UTC/GMT '.(new \DateTime(null, new \DateTimeZone('Asia/Shanghai')))->format('P').' - Asia/Shanghai'],
    ['id'=>'UTC', 'text'=> 'UTC/GMT '.(new \DateTime(null, new \DateTimeZone('UTC')))->format('P').' - UTC']
];

return $timezones;
