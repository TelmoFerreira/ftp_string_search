<!DOCTYPE html>
<html>
    <head>
        <title>Search Files</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container mt-5">
            <h2>File Search</h2>
            <form action="" method="GET">
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label for="searchTerm">Search Term:</label>
                            <input type="text" class="form-control" id="searchTerm" required name="q" placeholder="Enter search term">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label for="fileType">File Type:</label>
                            <select class="form-control" id="fileType" required name="type">
                                <option value="">All Types</option>
                                <option value="js">Text</option>
                                <option value="php">PHP</option>
                                <option value="html">HTML</option>
                                <!-- Add more options for other file types -->
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <label for="fileType">&nbsp; </label>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
            </form>

            <hr>

            <h3>Search Results</h3>
            <ul>
                <?php
                    function searchFiles($dir, $searchTerm, $fileType) {
                        $files = scandir($dir);
                        foreach ($files as $file) {
                            if ($file == '.' || $file == '..') {
                                continue;
                            }
                            $path = $dir . '/' . $file;
                            if (is_dir($path)) {
                                searchFiles($path, $searchTerm, $fileType);
                            } else {
                                if (!$fileType || pathinfo($path, PATHINFO_EXTENSION) == $fileType) {
                                    $content = file($path);  // Read file into an array of lines
                                    $matches = [];
                                    foreach ($content as $lineNumber => $lineContent) {
                                        if (strpos($lineContent, $searchTerm) !== false) {
                                            $matches[] = [
                                                'line' => $lineNumber + 1,
                                                'content' => htmlspecialchars($lineContent),
                                            ];
                                        }
                                    }
                                    if (!empty($matches)) {
                                        $numLines = count($matches);
                                        echo '
                                            <li>' . $path . ' (<a href="#" class="toggle-lines">'.$numLines.'<span style="font-size: 18px;" class="arrow">&#x25BE;</span></a>)
                                                <ul style="display: none;"  class="lines">';
                                                foreach ($matches as $match) {
                                                    echo '  
                                                        <li>
                                                            <strong>Line: ' . $match['line'] . '</strong> <span class="line-content">' . $match['content'] . '</span>
                                                        </li>';
                                                }
                                        echo '  </ul>
                                            </li>';
                                    }
                                }
                            }
                        }
                    }
                    if(isset($_GET['q']) && !empty($_GET['q'])) {
                        $searchTerm = $_GET['q'];
                        $fileType = $_GET['type'];
                        searchFiles('.', $searchTerm, $fileType);
                    }
                ?>
            </ul>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
        $(document).ready(function() {
            $('.toggle-lines').click(function(e) {
                e.preventDefault();
                var $arrow = $(this).find('.arrow');
                $(this).siblings('.lines').toggle();
                $arrow.toggleClass('rotate');
            });
        });
        </script>
        <style>
            .arrow {
                font-size: 12px;
                margin-left: 5px;
                display: inline-block;
                transition: transform 0.2s ease-in-out;
            }
            .rotate {
                transform: rotate(180deg);
            }
        </style>
    </body>
</html>
