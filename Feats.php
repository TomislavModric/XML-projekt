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
                <th>Prerequisite</th>
                <th>Description</th>
                <th>Modifiers</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $xml = simplexml_load_file('feats.xml');

            $feats = [];

            foreach ($xml->feat as $feat) {
                $modifiers = [];
                foreach ($feat->modifier as $modifier) {
                    $modifiers[] = (string) $modifier;
                }

                $feats[] = [
                    'name' => (string) $feat->name,
                    'prerequisite' => (string) $feat->prerequisite,
                    'description' => (string) $feat->text,
                    'modifiers' => $modifiers
                ];
            }

            usort($feats, function ($a, $b) {
                return $a['name'] <=> $b['name'];
            });

            $itemsPerPage = 15;
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
            $startIndex = ($currentPage - 1) * $itemsPerPage;
            $endIndex = $startIndex + $itemsPerPage - 1;
            $totalFeats = count($feats);

            for ($i = $startIndex; $i <= $endIndex && $i < $totalFeats; $i++) {
                echo '<tr>';
                echo '<td>' . $feats[$i]['name'] . '</td>';
                echo '<td>' . $feats[$i]['prerequisite'] . '</td>';
                echo '<td>' . $feats[$i]['description'] . '</td>';
                echo '<td>' . implode(', ', $feats[$i]['modifiers']) . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>

    <nav aria-label="Table pagination">
        <ul class="pagination justify-content-center">
            <?php
            $totalPages = ceil($totalFeats / $itemsPerPage);

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
        var feats = <?php echo json_encode($feats); ?>;

        function filterTable(searchTerm) {
            var filteredFeats = feats.filter(function (feat) {
                return feat.name.toLowerCase().includes(searchTerm.toLowerCase());
            });

            var tableBody = '';

            filteredFeats.forEach(function (feat, index) {
                tableBody += '<tr>';
                tableBody += '<td>' + feat.name + '</td>';
                tableBody += '<td>' + feat.prerequisite + '</td>';
                tableBody += '<td>' + feat.description + '</td>';
                tableBody += '<td>' + feat.modifiers.join(', ') + '</td>';
                tableBody += '</tr>';
            });

            $('tbody').html(tableBody);
        }

        $('#search-button').on('click', function () {
            var searchTerm = $('#search-input').val();
            filterTable(searchTerm);
        });

        $('#search-input').on('keypress', function (event) {
            if (event.which === 13) {
                var searchTerm = $('#search-input').val();
                filterTable(searchTerm);
            }
        });
    });
</script>

</body>
</html>
