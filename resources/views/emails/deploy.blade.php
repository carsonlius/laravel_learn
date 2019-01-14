@component('mail::message')
# Introduction

欢迎访问我的

@component('mail::button', ['url' => 'https://zhihu.carsonlius.vip/'])
网站
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
