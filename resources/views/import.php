<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SLV - Utilities</title>
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
</head>
<body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Streamline Verify - Utilities</a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Update Exclusion Lists</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="error-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Error</h4>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <div class="jumbotron">
            <h2>Update Exclusion Lists</h2>
            <p class="page-instructions">Click "Start" to update an Exclusion List in the Streamline Verify Exclusion Database.</p>
            <a href="#" class="create-tables-btn btn btn-info btn-lg">Create Tables</a>
        </div>

        <div class="table-responsive">

            <table class="table table-striped">
                <tr>
                    <th>List</th>
                    <th>Description</th>
                    <th>URL</th>
                    <th>Update</th>
                    <th>Ready For Update</th>
                </tr>
                <form name="importForm" method="get">
                <?php
                foreach ($exclusionLists as $prefix => $info)
                {
                    ?>
                    <tr>
                        <td><?= $info[0] ?></td>
                        <td><?= $info['description'] ?></td>
                        <td>
                        	<input class="url text_<?= $info['prefix'] ?>" type="text" name="text_<?= $info['prefix'] ?>" value="<?= $info['import_url'] ?>" />
                        </td>
                        <td>
                            <?php 
                            	$disabled = empty($info['import_url']) ? "disabled" : "";
							?>
							<input <?= $disabled ?> type="button" value="Start" data-action="/import/<?= $info['prefix'] ?>" class="start-btn btn btn-1g btn-default" />
                        </td>
                        <td class="readyForUpdate"><?= $info['ready_for_update'] === 'N' ? 'No' : 'Yes' ?></td>
                    </tr>
                    <?php
                }

                ?>
                </form>
            </table>
        </div>

    </div><!-- /.container -->
    <script src="/js/jquery-1.12.0.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/main.js"></script>
</body>
</html>