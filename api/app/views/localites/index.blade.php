<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laravel PHP Framework</title>
	
</head>
<body>
	@foreach ($localites as $localite)
    <p>Localite nom : {{ $localite->NomLocalite }} - Prénom : {{ $localite->IDRegion }} </p>
	@endforeach
</body>
</html>
