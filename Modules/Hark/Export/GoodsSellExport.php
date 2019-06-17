<?php

namespace Modules\Hark\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GoodsSellExport implements FromCollection, WithHeadings
{
    private $region_id;

    public function __construct($region_id)
    {
        $this->region_id = $region_id;
    }

    public function headings(): array
    {
        return [
            ['商品名', '店铺名', '销量'],
            //['Second row', 'Second row'],
        ];
    }

    public function collection()
    {
        $data = DB::connection('mysql_harkhark_v5')->table('t_goods')

            ->join('t_goods_description', function($join) {
                $join->on('t_goods.id', '=', 't_goods_description.id')->where('t_goods_description.language_id', 2);
            })
            ->join('store', function($join) {
                $join->on('t_goods.store_id', '=', 'store.id')->where('store.region_id', $this->region_id);
            })
            ->join('store_description', function($join){
                $join->on('t_goods.store_id', '=', 'store_description.id')->where('store_description.language_id', 2);
            })
            ->select('t_goods_description.title as goods_name', 'store_description.title as store_name', 't_goods.sell_count as sell_count')
            ->orderBy('t_goods.sell_count', 'DESC')->take(1000)->get();

        return $data;
    }
}