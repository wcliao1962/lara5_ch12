<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trans</title>
    {!! SEO::generate() !!}
</head>
<body>
    <h1>@lang('index.hello')</h1>
    @lang('I love programming')
</body>
</html>