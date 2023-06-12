<!DOCTYPE html>
<html>
<head>
    <title>Home - D&D Database</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">D&D</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="Races.php">Races</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Feats.php">Feats</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Spells.php">Spells</a>
                </li>
            </ul>
            <form class="form-inline ml-auto">
                <div class="input-group">
                    <input type="text" id="search-input" class="form-control" placeholder="Search">
                    <div class="input-group-append">
                        <button type="button" id="search-button" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </nav>
    <div class="container mt-4">
        <h1>Welcome to the D&D database!</h1>
    </div>
</body>
</html>
