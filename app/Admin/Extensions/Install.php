<?php

namespace App\Admin\Extensions;

use Encore\Admin\Admin;
use Encore\Admin\Form\Field\Html;
use SebastianBergmann\CodeCoverage\Report\Html\HTMLTest;

class Install
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    protected function script()
    {
        $url = route('core.module.install', $this->name);

        $installConfirm = trans('core::module.install.confirm', [module($this->name)->title]);
        $confirm = trans('admin.confirm');
        $cancel = trans('admin.cancel');

        return <<<SCRIPT

        $('.module_install').unbind('click').click(function(){


            swal({
                title: "$installConfirm",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "$confirm",
                showLoaderOnConfirm: true,
                cancelButtonText: "$cancel",
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        $.ajax({
                            method: 'post',
                            url: '{$url}',
                            data: {
                                _token:LA.token,
                            },
                            success: function (data) {
                                $.pjax.reload('#pjax-container');

                                resolve(data);
                            }
                        });
                    });
                }
            }).then(function(result) {
                var data = result.value;
                if (typeof data === 'object') {
                    if (data.status) {
                        swal(data.message, '', 'success');
                    } else {
                        swal(data.message, '', 'error');
                    }
                }
            });



        });




SCRIPT;

    }


    protected function render()
    {
        Admin::script($this->script());

        return <<<HTML

        <a href='javascript:void(0);' class='module_install' data-name='{$this->name}'><i class='fa fa-download'></i></a>
HTML;


    }

    public function __toString()
    {
        return $this->render();
    }
}