<?php
/*
$timezones = [

    ['id'=>'Asia/Shanghai', 'text'=> 'UTC/GMT '.(new \DateTime(null, new \DateTimeZone('Asia/Shanghai')))->format('P').' - Asia/Shanghai'],
    ['id'=>'UTC', 'text'=> 'UTC/GMT '.(new \DateTime(null, new \DateTimeZone('UTC')))->format('P').' - UTC']
];

return $timezones;
*/

$timezones = [

    'Asia/Shanghai'=> 'UTC/GMT '.(new \DateTime(null, new \DateTimeZone('Asia/Shanghai')))->format('P').' - Asia/Shanghai',
    'UTC'          =>'UTC/GMT '.(new \DateTime(null, new \DateTimeZone('UTC')))->format('P').' - UTC'
];

return $timezones;
