<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laravel PHP Framework</title>
	
</head>
<body>
	@foreach ($comites as $comite)
    <p>comite nom : {{ $comite->NomComite }} - Num : {{ $comite->IDNumAppel }} </p>
	@endforeach
</body>
</html>
