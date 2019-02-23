<!DOCTYPE html>
<html lang="jp">
<head>
	<meta charset="UTF-8">
	<title>Captcha</title>
</head>
<body>
	{{ $msg ?? '' }}
	<form action="{{ action('CaptchaController@captcha') }}" method="POST">
		@csrf
		{!! no_captcha()->display() !!}<br />
		@if ($errors->has('g-recaptcha-response'))
			{{ $errors->first('g-recaptcha-response') }}<br />
		@endif
		<button type="submit">送出</button>
    </form>
    {{-- 引入API --}}
    {!! no_captcha()->script()->toHtml() !!}
</body>
</html>
