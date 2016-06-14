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
                    <th class="text-center" style="width:110px;">Start Update</th>
                    <th class="text-center">Update<br>Required</th>
                </tr>
                <form name="importForm" method="get">
                <?php
                foreach ($exclusionLists as $prefix => $info)
                {
                    $lastImportedDate = $info['last_imported_date'] ? $info['last_imported_date'] : '--';
                    $lastImportStats = $info['last_import_stats'] ? json_decode($info['last_import_stats']) : null;
                    
                    $added = $lastImportStats ? $lastImportStats->added : 0;
                    $deleted = $lastImportStats ? $lastImportStats->deleted : 0;
                    $previousRecordCount = $lastImportStats ? $lastImportStats->previousRecordCount : 0;
                    $currentRecordCount = $lastImportStats ? $lastImportStats->currentRecordCount : 0;                  
                ?>
                    <tr>
                        <td><?= $info['accr'] ?></td>
                        <td>
                        	<?= $info['description'] ?>
                        	<br />
                        	<span class="small import-stat">
                        		Last imported on <span id="<?= $info['prefix'] ?>-last-import-ts"><?= $lastImportedDate ?></span>
                       		</span>
                       		<br />
                       		<span class="small import-stat">
                        		Staging : <span id="<?= $info['prefix'] ?>-current-record-count"><?= $currentRecordCount ?></span>
                       		</span>
							<span class="small import-stat">
                        		Prod : <span id="<?= $info['prefix'] ?>-previous-record-count"><?= $previousRecordCount ?></span>
                       		</span>                       		
                       		<span class="small import-stat">
                        		Added : <span id="<?= $info['prefix'] ?>-added"><?= $added ?></span>
                       		</span>
							<span class="small import-stat">
                        		Deleted : <span id="<?= $info['prefix'] ?>-deleted"><?= $deleted ?></span>
                       		</span>
                        </td>
                        <td>
                        	<input class="url text_<?= $info['prefix'] ?>" type="text" name="text_<?= $info['prefix'] ?>" value="<?= $info['import_url'] ?>" />
                        </td>
                        <td>
                            <?php 
                            	$disabled = empty($info['import_url']) || ! $info['update_required'] ? "disabled" : "";
							?>
							<input <?= $disabled ?> type="button" value="Start" data-action="/import/<?= $info['prefix'] ?>" class="start-btn btn btn-1g btn-default" />
                        </td>
                        <td class="readyForUpdate text-center"><?= $info['update_required'] ? 'Yes' : 'No' ?></td>
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