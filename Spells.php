<!DOCTYPE html>
<html>
<head>
    <title>PHP Bootstrap Table</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <style>
        .search-box {
            margin-bottom: 20px;
        }
    </style>
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
        <h3>Table</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Level</th>
                    <th>School</th>
                    <th>Time</th>
                    <th>Range</th>
                    <th>Components</th>
                    <th>Duration</th>
                    <th>Classes</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Read the XML file
                $xml = simplexml_load_file('spells.xml');

                // Create an array to hold the spells
                $spells = [];

                // Populate the array with spell data
                foreach ($xml->spell as $spell) {
                    $spells[] = [
                        'name' => (string) $spell->name,
                        'level' => (int) $spell->level,
                        'school' => (string) $spell->school,
                        'time' => (string) $spell->time,
                        'range' => (string) $spell->range,
                        'components' => (string) $spell->components,
                        'duration' => (string) $spell->duration,
                        'classes' => (string) $spell->classes
                    ];
                }

                // Sort the spells array by level
                usort($spells, function ($a, $b) {
                    return $a['level'] <=> $b['level'];
                });

                // Pagination
                $itemsPerPage = 15;
                $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
                $startIndex = ($currentPage - 1) * $itemsPerPage;
                $endIndex = $startIndex + $itemsPerPage - 1;
                $totalSpells = count($spells);

                // Display table rows for the current page
                for ($i = $startIndex; $i <= $endIndex && $i < $totalSpells; $i++) {
                    echo '<tr>';
                    echo '<td>' . $spells[$i]['name'] . '</td>';
                    echo '<td>' . $spells[$i]['level'] . '</td>';
                    echo '<td>' . $spells[$i]['school'] . '</td>';
                    echo '<td>' . $spells[$i]['time'] . '</td>';
                    echo '<td>' . $spells[$i]['range'] . '</td>';
                    echo '<td>' . $spells[$i]['components'] . '</td>';
                    echo '<td>' . $spells[$i]['duration'] . '</td>';
                    echo '<td>' . $spells[$i]['classes'] . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>

        <nav aria-label="Table pagination">
            <ul class="pagination justify-content-center">
                <?php
                // Calculate the number of pages
                $totalPages = ceil($totalSpells / $itemsPerPage);

                // Generate pagination links
                if ($totalPages > 1) {
                    $prevPage = $currentPage - 1;
                    $nextPage = $currentPage + 1;

                    echo '<li class="page-item ' . ($currentPage == 1 ? "disabled" : "") . '">';
                    echo '<a class="page-link" href="?page=' . $prevPage . '" aria-label="Previous">';
                    echo '<span aria-hidden="true">&laquo;</span>';
                    echo '<span class="sr-only">Previous</span>';
                    echo '</a>';
                    echo '</li>';

                    for ($i = 1; $i <= $totalPages; $i++) {
                        $activeClass = ($i == $currentPage) ? 'active' : '';
                        echo '<li class="page-item ' . $activeClass . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                    }

                    echo '<li class="page-item ' . ($currentPage == $totalPages ? "disabled" : "") . '">';
                    echo '<a class="page-link" href="?page=' . $nextPage . '" aria-label="Next">';
                    echo '<span aria-hidden="true">&raquo;</span>';
                    echo '<span class="sr-only">Next</span>';
                    echo '</a>';
                    echo '</li>';
                }
                ?>
            </ul>
        </nav>
    </div>

    <script>
        $(document).ready(function () {
            var spells = <?php echo json_encode($spells); ?>;

            function filterTable(searchTerm) {
                var filteredSpells = spells.filter(function (spell) {
                    return spell.name.toLowerCase().includes(searchTerm.toLowerCase());
                });

                var tableBody = '';

                filteredSpells.forEach(function (spell, index) {
                    tableBody += '<tr>';
                    tableBody += '<td>' + spell.name + '</td>';
                    tableBody += '<td>' + spell.level + '</td>';
                    tableBody += '<td>' + spell.school + '</td>';
                    tableBody += '<td>' + spell.time + '</td>';
                    tableBody += '<td>' + spell.range + '</td>';
                    tableBody += '<td>' + spell.components + '</td>';
                    tableBody += '<td>' + spell.duration + '</td>';
                    tableBody += '<td>' + spell.classes + '</td>';
                    tableBody += '</tr>';
                });

                $('tbody').html(tableBody);
            }

            // Handle search button click
            $('#search-button').on('click', function () {
                var searchTerm = $('#search-input').val();
                filterTable(searchTerm);
            });

            // Handle enter key press
            $('#search-input').on('keypress', function (event) {
                if (event.which === 13) {
                    var searchTerm = $(this).val();
                    filterTable(searchTerm);
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
