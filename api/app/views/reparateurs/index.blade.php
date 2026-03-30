<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laravel PHP Framework</title>
	
</head>
<body>
	@foreach ($reparateurs as $reparateur)
    <p>reparateur nom : {{ $reparateur->NomRep }} - Prénom : {{ $reparateur->PrenomsRep  }} </p>
	@endforeach
</body>
</html>
