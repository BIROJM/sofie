<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laravel PHP Framework</title>
	
</head>
<body>
	@foreach ($agents as $agent)
    <p>Agent nom : {{ $agent->NomAgent }} - Prénom : {{ $agent->PrenomsAgent }} </p>
	@endforeach
</body>
</html>
