<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Carbon</title>
</head>
<body>
	今天是：{{ $tw_now->toDateString() }}<br />
	現在台灣時間是：{{ $tw_now }}<br />
	現在日本時間是：{{ $ja_now }}<br />
	明年的元旦是星期{{ $next_year_day }}<br />
	距離現在還有 {{ $next_year->diffForHumans($tw_now) }}
</body>
</html>