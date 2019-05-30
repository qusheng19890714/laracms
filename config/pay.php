<?php

return [
    'alipay' => [
        'app_id'         => '2016092900623675',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvRs9onK7lt6+8N82NUmvLHLC6B4yTHvg3VX6I9WR/ReJ1Z4hSNlq7GKrjWFchhF7YMjPzhBQuODzSzz0XDhTtoNPWBPIqzD55bOdYSY2zFKPooTkmElpv2JbVWSBVvpamWuYH524s7C5KK5U0eWRQyOWAKfxSLw8tKhQVzQax0+0UkIBvTCavuXW86nTVk9EiMsOTEARYSA06E6X4L2L5cesmV8rf9s+PFwODB/92zFe9mhhk5CPtA58ekxJ0VUiV5v8H9AMC0I+g4qXpLC9RACnt3jY2dqUT8V3C+6FCMTizdrlpU5sgVUPk/TD36oudetUsVxmSiGWsU6mVI+vQwIDAQAB',
        'private_key'    => 'MIIEpQIBAAKCAQEAvRs9onK7lt6+8N82NUmvLHLC6B4yTHvg3VX6I9WR/ReJ1Z4hSNlq7GKrjWFchhF7YMjPzhBQuODzSzz0XDhTtoNPWBPIqzD55bOdYSY2zFKPooTkmElpv2JbVWSBVvpamWuYH524s7C5KK5U0eWRQyOWAKfxSLw8tKhQVzQax0+0UkIBvTCavuXW86nTVk9EiMsOTEARYSA06E6X4L2L5cesmV8rf9s+PFwODB/92zFe9mhhk5CPtA58ekxJ0VUiV5v8H9AMC0I+g4qXpLC9RACnt3jY2dqUT8V3C+6FCMTizdrlpU5sgVUPk/TD36oudetUsVxmSiGWsU6mVI+vQwIDAQABAoIBAQCwZs7R0JDopZQcATwB7WA46DykZjapXg1eqqsR7lGmc+ShnkaSPC4fn9NDqQS0E26x0+D06gdCzqRlFNEljW2ZnVfQY3QXLTKPcUNnskv/wSw3gBv1atX4L3nfaEe2qQcGgnV/WJNJG5s9NZHt0mX06ScuKXtMTDuckj2Pew1XyYX9BwWLYO77KYmxwUUqb763viO9I1cqUCK1WsMa0ZHeW+R8ywNo3pTMMA7oAUcLLeA5iRzaVWYDaHKlqV7lxbAKvCTJWFB2r1EaOSibY7BralXgOB8QwvEtqqtyP7H1bbkC3BuoxrLiA/OtlbQkPkCMitK0QOiW0QwDFvRk/dyhAoGBAPq+yjVi/JmQpwPE2uSOGWsmTjDxqv3dzzR/dUzaN9H7OIxIK9Z2aR/WW/Jt6qWZi8d74UBbhgmrGDgnndGO9xj5LltzVBdFDBfi7dmABHUyE0sfIffcwREVkqgEGQ2/y9WxoTvmG/689vg3xkGpdqAkCpN/S8g1rdCOCVmH6Tb5AoGBAMERxI2a1vkddatlXiI/eb2mEh5WvfZAgvihm9Oh8ZGdpuywx1OlqmAUqIzy54lD6aIRMHaSQYvRnAAblySWd5KowYfk5vtNeCwZJROd3fRoNk2NGZ5PrXVBVatbgQ+26/nWOvWDHwzj0hqJQj3L1AspzlUtmCFtR4zeX5/7B7sbAoGBAMrVBxGEjz1R7Ch6V29HG3y3scyZ0W4mvXSEHkkaxMjRZX7k6sFDa+pbJmnHTGbE/HNT3HSJFLZBdwgF0/4unefPuhY0BrQEILI52Zl1myWKalz8RCgSpQLC5q2PEw0yNxsX44tmqwK3rThBNdjr0o/cV8nU0WKVOZNZMmsALEFRAoGAV051l6Nhex17TJp2Sv5xSvWCU/3dwTHDCDPsh3NADh1AkuAOAyFzPieZYMOnOEdhq0wiojvZMCUFancPjhgM0mFFwvIcEaAiq65jOc+1wwQtKjyYTSKyycdVujuSzUxmwX/DAardq7KKHD3dmpxFsxagm5wWo2cLaARXMb0O7ekCgYEAu1nijbbeLPG7b/JDoc5/Zj9U33MgB7OPnhahSKfJFtQJwoxjCND4qkQAz23k2lJ/xVoLmG4HyCOetqa1oQBpkISuFWAmdbaVtsKWHm0BKNSWkaFut9dr7pGgEmnM32ARH3l1eMf7uuG1jCYVMyVtIPPb3rRxJwudSH5mUHmVT2w=',
        'log'            => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],

    'wechat' => [
        'app_id'      => '',
        'mch_id'      => '',
        'key'         => '',
        'cert_client' => '',
        'cert_key'    => '',
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];